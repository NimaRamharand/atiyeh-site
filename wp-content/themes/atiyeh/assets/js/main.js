/**
 * Atiyeh theme scripts — small, dependency-free modules.
 * Each module only runs when its markup exists on the page.
 */
(() => {
	'use strict';
	const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	/* Header: solid background after scrolling, mobile menu, search panel. */
	function initHeader() {
		const header = document.querySelector('[data-header]');
		if (!header) return;
		const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 40);
		onScroll();
		window.addEventListener('scroll', onScroll, { passive: true });

		const navBtn = header.querySelector('[data-nav-toggle]');
		const searchBtn = header.querySelector('[data-search-toggle]');
		const search = header.querySelector('[data-search]');

		const setNav = (open) => {
			header.classList.toggle('nav-open', open);
			navBtn?.setAttribute('aria-expanded', String(open));
			navBtn?.setAttribute('aria-label', open ? 'بستن منو' : 'باز کردن منو');
		};
		const setSearch = (open) => {
			if (!search) return;
			search.hidden = !open;
			searchBtn?.setAttribute('aria-expanded', String(open));
			if (open) search.querySelector('input')?.focus();
		};
		navBtn?.addEventListener('click', () => { setSearch(false); setNav(!header.classList.contains('nav-open')); });
		searchBtn?.addEventListener('click', () => { setNav(false); setSearch(search.hidden); });
		document.addEventListener('keydown', (e) => {
			if (e.key !== 'Escape') return;
			if (header.classList.contains('nav-open')) { setNav(false); navBtn?.focus(); }
			if (search && !search.hidden) { setSearch(false); searchBtn?.focus(); }
		});
		header.querySelectorAll('.site-nav a').forEach((a) => a.addEventListener('click', () => setNav(false)));
	}

	const motionOn = document.documentElement.classList.contains('js-motion');
	const finePointer = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
	const REVEAL = ['rv', 'rvl', 'rvr', 'rvs', 'rvc'];

	/* Splits a heading into word spans so the words can rise one by one (text nodes only; markup kept). */
	function splitWords(el) {
		if (el.classList.contains('split')) return;
		let i = 0;
		const walk = (node) => {
			[...node.childNodes].forEach((n) => {
				if (n.nodeType === 1) { walk(n); return; }
				if (n.nodeType !== 3 || !n.textContent.trim()) return;
				const frag = document.createDocumentFragment();
				n.textContent.split(/(\s+)/).forEach((part) => {
					if (!part) return;
					if (/^\s+$/.test(part)) { frag.append(part); return; }
					const outer = document.createElement('span');
					outer.className = 'wd';
					const inner = document.createElement('span');
					inner.textContent = part;
					inner.style.setProperty('--wi', i++);
					outer.append(inner);
					frag.append(outer);
				});
				n.replaceWith(frag);
			});
		};
		walk(el);
		el.classList.add('split');
	}

	/*
	 * Entrance animations. Elements with rv/rvl/rvr/rvs/rvc play once when they enter the viewport;
	 * elements entering together are staggered. Once played, the classes are removed so hover effects
	 * (which also use transform) work normally. Big headings get the word-by-word rise.
	 */
	function initReveal() {
		const headings = document.querySelectorAll('.section-title, .about__title, .trust__title, .request__title, .careers__title, .bento__title, .page-hero__title, .hero-slide__title, .split-me');
		if (!motionOn || !('IntersectionObserver' in window)) {
			document.documentElement.classList.remove('js-motion');
			return;
		}
		headings.forEach(splitWords);
		const done = (el) => (e) => {
			if (e.target !== el || !REVEAL.includes(e.animationName)) return;
			el.classList.remove(...REVEAL, 'is-visible');
			el.style.removeProperty('--rd');
		};
		const io = new IntersectionObserver((entries) => {
			const shown = entries.filter((en) => en.isIntersecting).map((en) => en.target)
				.sort((x, y) => (x.compareDocumentPosition(y) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1));
			shown.forEach((el, k) => {
				io.unobserve(el);
				if (el.matches('[data-seen]') && !REVEAL.some((c) => el.classList.contains(c))) { el.classList.add('seen'); return; }
				el.style.setProperty('--rd', Math.min(k, 7) * 0.09 + 's');
				el.addEventListener('animationend', done(el));
				el.classList.add('is-visible', 'seen');
				el.querySelectorAll('.split').forEach((h) => { h.style.setProperty('--wd', (Math.min(k, 7) * 0.09 + 0.15) + 's'); h.classList.add('words-in'); });
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
		document.querySelectorAll(REVEAL.map((c) => '.' + c).join(',') + ',[data-seen]').forEach((el) => io.observe(el));

		// Headings that are not inside a reveal element animate on their own.
		const hio = new IntersectionObserver((entries) => entries.forEach((en) => {
			if (!en.isIntersecting) return;
			en.target.classList.add('words-in');
			hio.unobserve(en.target);
		}), { threshold: 0.3 });
		headings.forEach((h) => {
			if (h.closest('[data-slide]')) return; // the slider drives these
			if (!h.closest(REVEAL.map((c) => '.' + c).join(','))) hio.observe(h);
		});
	}

	/* Scroll progress bar, back-to-top button, hide-on-scroll header. */
	function initScrollUI() {
		const bar = document.querySelector('[data-scroll-progress]');
		const top = document.querySelector('[data-to-top]');
		const header = document.querySelector('[data-header]');
		let lastY = window.scrollY, ticking = false;
		const update = () => {
			ticking = false;
			const y = window.scrollY;
			const max = document.documentElement.scrollHeight - window.innerHeight;
			const p = max > 0 ? Math.min(1, y / max) : 0;
			bar?.style.setProperty('--p', p.toFixed(4));
			if (top) { top.style.setProperty('--p', p.toFixed(4)); top.classList.toggle('is-shown', y > 600); }
			if (header && !reduceMotion) {
				const hide = y > 260 && y > lastY + 4 && !header.classList.contains('nav-open') && !header.contains(document.activeElement);
				if (hide) header.classList.add('is-hidden');
				else if (y < lastY - 4 || y <= 260) header.classList.remove('is-hidden');
			}
			lastY = y;
		};
		window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
		window.addEventListener('resize', update);
		update();
		top?.addEventListener('click', () => {
			window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
			document.querySelector('.skip-link')?.focus({ preventScroll: true });
		});
	}

	/* Light / dark switch: remembered per browser; circular reveal where View Transitions exist. */
	function initTheme() {
		const btn = document.querySelector('[data-theme-toggle]');
		if (!btn) return;
		const root = document.documentElement;
		const sync = () => {
			const dark = root.getAttribute('data-theme') === 'dark';
			btn.setAttribute('aria-pressed', String(dark));
			btn.setAttribute('aria-label', dark ? 'حالت روز' : 'حالت شب');
			document.querySelector('meta[name="theme-color"]')?.setAttribute('content', dark ? '#0b1220' : '#1757b8');
		};
		const apply = (dark) => {
			if (dark) root.setAttribute('data-theme', 'dark'); else root.removeAttribute('data-theme');
			try { localStorage.setItem('atiyeh-theme', dark ? 'dark' : 'light'); } catch (e) { /* private mode */ }
			sync();
		};
		sync();
		btn.addEventListener('click', () => {
			const dark = root.getAttribute('data-theme') !== 'dark';
			if (reduceMotion) { apply(dark); return; }
			if (!document.startViewTransition) {
				root.classList.add('theme-fade');
				apply(dark);
				setTimeout(() => root.classList.remove('theme-fade'), 500);
				return;
			}
			const r = btn.getBoundingClientRect();
			const x = r.left + r.width / 2, y = r.top + r.height / 2;
			const end = Math.hypot(Math.max(x, innerWidth - x), Math.max(y, innerHeight - y));
			root.classList.add('vt-theme');
			const vt = document.startViewTransition(() => apply(dark));
			vt.ready.then(() => root.animate(
				{ clipPath: [`circle(0px at ${x}px ${y}px)`, `circle(${end}px at ${x}px ${y}px)`] },
				{ duration: 700, easing: 'cubic-bezier(.2,.8,.2,1)', pseudoElement: '::view-transition-new(root)' }
			));
			vt.finished.finally(() => root.classList.remove('vt-theme'));
		});
	}

	/* Pointer effects: card spotlight, gentle 3D tilt, magnetic buttons, click ripple, hero parallax. */
	function initPointerFX() {
		document.addEventListener('pointerdown', (e) => {
			const host = e.target.closest('.btn, .chip--btn, .icon-btn, .hero__arrow, .ff-btn-submit');
			if (!host || reduceMotion) return;
			const r = host.getBoundingClientRect();
			const size = Math.max(r.width, r.height) * 2.2;
			const dot = document.createElement('span');
			dot.className = 'ripple';
			dot.setAttribute('aria-hidden', 'true');
			dot.style.cssText = `width:${size}px;height:${size}px;left:${e.clientX - r.left - size / 2}px;top:${e.clientY - r.top - size / 2}px`;
			if (getComputedStyle(host).position === 'static') host.style.position = 'relative';
			host.style.overflow = 'hidden';
			host.append(dot);
			dot.addEventListener('animationend', () => dot.remove());
		});
		if (!finePointer || reduceMotion) return;

		document.querySelectorAll('.area-card, .step, .project-card, .bento__card, .plaque__link, .job, .about__link, .link-tile, .value-card, .team-card, .cert, .perk, .svc-feature, .quick-card, .org-node').forEach((el) => {
			const spot = document.createElement('span');
			spot.className = 'spot';
			spot.setAttribute('aria-hidden', 'true');
			el.classList.add('has-spot');
			el.append(spot);
			el.addEventListener('pointermove', (e) => {
				const r = el.getBoundingClientRect();
				el.style.setProperty('--sx', (e.clientX - r.left) + 'px');
				el.style.setProperty('--sy', (e.clientY - r.top) + 'px');
			});
		});

		document.querySelectorAll('.bento__card, .project-card, .plaque__link, .stat, .team-card, .cert, .hero-slide__visual, .page-hero__visual').forEach((el) => {
			el.classList.add('tilt');
			if (el.parentElement) el.parentElement.style.perspective = '1100px';
			const max = el.matches('.stat') ? 10 : 6;
			el.addEventListener('pointermove', (e) => {
				const r = el.getBoundingClientRect();
				const x = (e.clientX - r.left) / r.width - 0.5;
				const y = (e.clientY - r.top) / r.height - 0.5;
				const mag = Math.min(1, Math.hypot(x, y) * 2);
				el.style.rotate = `${(-y).toFixed(3)} ${x.toFixed(3)} 0 ${(mag * max).toFixed(2)}deg`;
			});
			el.addEventListener('pointerleave', () => { el.style.rotate = ''; });
		});

		document.querySelectorAll('.btn, .hero__arrow, .to-top').forEach((el) => {
			el.addEventListener('pointermove', (e) => {
				const r = el.getBoundingClientRect();
				const x = (e.clientX - r.left - r.width / 2) / r.width;
				const y = (e.clientY - r.top - r.height / 2) / r.height;
				el.style.translate = `${(x * 10).toFixed(1)}px ${(y * 8).toFixed(1)}px`;
			});
			el.addEventListener('pointerleave', () => { el.style.translate = ''; });
		});

		document.querySelectorAll('.hero, .page-hero').forEach((el) => {
			el.addEventListener('pointermove', (e) => {
				const r = el.getBoundingClientRect();
				el.style.setProperty('--mx', ((e.clientX - r.left) / r.width - 0.5).toFixed(3));
				el.style.setProperty('--my', ((e.clientY - r.top) / r.height - 0.5).toFixed(3));
			});
			el.addEventListener('pointerleave', () => { el.style.setProperty('--mx', 0); el.style.setProperty('--my', 0); });
		});
	}

	/* Hero slider: auto-advance 5.5 s; pauses on hover / focus / tab hidden; arrows, dots, keyboard; pause button. */
	function initSlider() {
		const root = document.querySelector('[data-slider]');
		if (!root) return;
		const slides = [...root.querySelectorAll('[data-slide]')];
		const dots = [...root.querySelectorAll('[data-slide-to]')];
		const counter = root.querySelector('[data-slider-counter]');
		const pauseBtn = root.querySelector('[data-slider-pause]');
		const fa = (n) => String(n).replace(/\d/g, (d) => '۰۱۲۳۴۵۶۷۸۹'[d]);
		const DELAY = 5500;
		let cur = 0, timer = 0, started = 0, left = DELAY, hovered = false, focused = false, userPaused = reduceMotion;
		root.style.setProperty('--slide-delay', DELAY + 'ms');

		const animateTitle = (slide, on) => slide.querySelectorAll('.split').forEach((h) => h.classList.toggle('words-in', on));
		const go = (n) => {
			cur = (n + slides.length) % slides.length;
			slides.forEach((s, k) => {
				const on = k === cur;
				s.classList.toggle('is-active', on);
				s.toggleAttribute('inert', !on);
				if (on) s.removeAttribute('aria-hidden'); else s.setAttribute('aria-hidden', 'true');
				animateTitle(s, false);
			});
			// Next frame: replay the word rise on the slide that just became active.
			requestAnimationFrame(() => requestAnimationFrame(() => animateTitle(slides[cur], true)));
			dots.forEach((d, k) => {
				d.classList.toggle('is-active', k === cur);
				if (k === cur) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
			});
			if (counter) counter.textContent = fa(cur + 1) + ' / ' + fa(slides.length);
			clearTimeout(timer); timer = 0; started = 0; left = DELAY;
			schedule();
		};
		// Keeps the remaining time across hover/focus pauses so the dot timer and the slide change stay in sync.
		const schedule = () => {
			clearTimeout(timer);
			const hold = userPaused || hovered || focused || document.hidden;
			if (timer && started) left = Math.max(0, left - (performance.now() - started));
			timer = 0; started = 0;
			root.classList.toggle('is-hold', hold && !userPaused);
			if (!hold) { started = performance.now(); timer = setTimeout(() => go(cur + 1), left); }
		};
		const setPaused = (p) => {
			userPaused = p;
			root.classList.toggle('is-paused', p);
			pauseBtn?.setAttribute('aria-label', p ? 'ادامه نمایش خودکار' : 'توقف نمایش خودکار');
			if (!p) { clearTimeout(timer); timer = 0; started = 0; left = DELAY; dots[cur]?.classList.remove('is-active'); void dots[cur]?.offsetWidth; dots[cur]?.classList.add('is-active'); }
			schedule();
		};
		requestAnimationFrame(() => animateTitle(slides[cur], true));

		root.querySelector('[data-slider-prev]')?.addEventListener('click', () => go(cur - 1));
		root.querySelector('[data-slider-next]')?.addEventListener('click', () => go(cur + 1));
		dots.forEach((d) => d.addEventListener('click', () => go(Number(d.dataset.slideTo))));
		pauseBtn?.addEventListener('click', () => setPaused(!userPaused));
		root.addEventListener('mouseenter', () => { hovered = true; schedule(); });
		root.addEventListener('mouseleave', () => { hovered = false; schedule(); });
		root.addEventListener('focusin', () => { focused = true; schedule(); });
		root.addEventListener('focusout', (e) => { if (!root.contains(e.relatedTarget)) { focused = false; schedule(); } });
		root.addEventListener('keydown', (e) => {
			// RTL: ArrowLeft moves forward (next), ArrowRight moves back.
			if (e.key === 'ArrowLeft') { e.preventDefault(); go(cur + 1); }
			else if (e.key === 'ArrowRight') { e.preventDefault(); go(cur - 1); }
		});
		document.addEventListener('visibilitychange', schedule);
		setPaused(userPaused);
	}

	/* Project filter chips (homepage + project archive). */
	function initFilter() {
		document.querySelectorAll('[data-filter]').forEach((root) => {
			const btns = [...root.querySelectorAll('[data-filter-btn]')];
			const items = [...root.querySelectorAll('[data-filter-item]')];
			const empty = root.querySelector('[data-filter-empty]');
			btns.forEach((b) => b.addEventListener('click', () => {
				const v = b.dataset.filterBtn;
				btns.forEach((x) => { const on = x === b; x.classList.toggle('is-active', on); x.setAttribute('aria-pressed', String(on)); });
				let shown = 0;
				items.forEach((it) => {
					const ok = v === 'all' || it.dataset.filterItem.split(' ').includes(v);
					it.hidden = !ok;
					it.classList.remove('is-in');
					if (ok) {
						it.style.setProperty('--fd', (shown * 0.08) + 's');
						if (!reduceMotion) { void it.offsetWidth; it.classList.add('is-in'); }
						shown++;
					}
				});
				root.classList.toggle('is-filtered', v !== 'all');
				if (empty) empty.hidden = shown > 0;
			}));
		});
	}

	/* Count-up for numeric stats (placeholders like «[ ۰۰ ]» are left untouched). */
	function initCounters() {
		const els = document.querySelectorAll('[data-count]');
		if (!els.length) return;
		const toLatin = (s) => s.replace(/[۰-۹]/g, (d) => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
		const fa = (n) => n.toLocaleString('fa-IR');
		const run = (el) => {
			const raw = el.textContent.trim();
			const plus = raw.endsWith('+') ? '+' : '';
			const target = parseInt(toLatin(raw).replace(/[^\d]/g, ''), 10);
			if (!target || reduceMotion) return;
			const t0 = performance.now(), dur = 1600;
			const step = (t) => {
				const p = Math.min(1, (t - t0) / dur);
				el.textContent = fa(Math.round(target * (1 - Math.pow(1 - p, 3)))) + plus;
				if (p < 1) requestAnimationFrame(step);
			};
			requestAnimationFrame(step);
		};
		if (!('IntersectionObserver' in window)) return;
		const io = new IntersectionObserver((entries) => entries.forEach((en) => {
			if (en.isIntersecting) { run(en.target); io.unobserve(en.target); }
		}), { threshold: 0.5 });
		els.forEach((el) => io.observe(el));
	}

	/* Static request form (until the Fluent Forms form replaces it in phase 4): no fake submission. */
	function initDemoForms() {
		document.querySelectorAll('[data-demo-form]').forEach((form) => form.addEventListener('submit', (e) => {
			e.preventDefault();
			let note = form.querySelector('.req-form__note');
			if (!note) {
				note = document.createElement('p');
				note.className = 'req-form__note';
				note.setAttribute('role', 'status');
				form.append(note);
			}
			note.textContent = 'ارسال فرم هنوز فعال نشده است.';
		}));
	}

	/* Appreciation gallery: open plaques in a <dialog> lightbox (falls back to the image link). */
	function initLightbox() {
		const dlg = document.querySelector('[data-lightbox-dialog]');
		if (!dlg || typeof dlg.showModal !== 'function') return;
		const img = dlg.querySelector('[data-lightbox-img]');
		const cap = dlg.querySelector('[data-lightbox-caption]');
		let opener = null;
		document.querySelectorAll('[data-lightbox]').forEach((a) => a.addEventListener('click', (e) => {
			e.preventDefault();
			opener = a;
			img.src = a.href;
			img.alt = a.dataset.caption || '';
			cap.textContent = a.dataset.caption || '';
			dlg.showModal();
		}));
		dlg.querySelector('[data-lightbox-close]')?.addEventListener('click', () => dlg.close());
		dlg.addEventListener('click', (e) => { if (e.target === dlg) dlg.close(); });
		dlg.addEventListener('close', () => opener?.focus());
	}

	/* Résumé upload: check type and size before sending (the server checks again). */
	function initResumeForm() {
		document.querySelectorAll('[data-resume-form]').forEach((form) => {
			const input = form.querySelector('input[type="file"]');
			if (!input) return;
			const max = Number(input.dataset.max || 0);
			const check = () => {
				const f = input.files[0];
				let msg = '';
				if (f && !/\.(pdf|docx)$/i.test(f.name)) msg = 'فقط فایل PDF یا DOCX پذیرفته می‌شود.';
				else if (f && max && f.size > max) msg = 'حجم فایل رزومه باید حداکثر ۵ مگابایت باشد.';
				input.setCustomValidity(msg);
				return !msg;
			};
			const label = form.querySelector('[data-file-name]');
			input.addEventListener('change', () => {
				if (label) label.textContent = input.files[0] ? input.files[0].name : 'فایلی انتخاب نشده است';
				if (!check()) input.reportValidity();
			});
			form.addEventListener('submit', (e) => { if (!check()) { e.preventDefault(); input.reportValidity(); } });
		});
	}

	/* Move focus to a server-side status message (after a form redirect) so screen readers announce it. */
	function initFocusMessages() {
		document.querySelector('[data-focus]')?.focus({ preventScroll: false });
	}

	const modules = [initHeader, initTheme, initScrollUI, initReveal, initPointerFX, initSlider, initFilter, initCounters, initDemoForms, initLightbox, initResumeForm, initFocusMessages];
	window.atiyeh = { modules, reduceMotion };
	const boot = () => modules.forEach((fn) => fn());
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
	else boot();
})();
