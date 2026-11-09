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

	/* Scroll reveal fallback for browsers without animation-timeline. */
	function initReveal() {
		if (CSS.supports('animation-timeline: view()') || reduceMotion || !('IntersectionObserver' in window)) return;
		document.documentElement.classList.add('js-reveal');
		const io = new IntersectionObserver((entries) => {
			entries.forEach((en) => {
				if (en.isIntersecting) { en.target.classList.add('is-visible'); io.unobserve(en.target); }
			});
		}, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
		document.querySelectorAll('.rv, .rvl, .rvr, .rvs').forEach((el) => io.observe(el));
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
		let cur = 0, timer = 0, hovered = false, focused = false, userPaused = reduceMotion;

		const go = (n) => {
			cur = (n + slides.length) % slides.length;
			slides.forEach((s, k) => {
				const on = k === cur;
				s.classList.toggle('is-active', on);
				s.toggleAttribute('inert', !on);
				if (on) s.removeAttribute('aria-hidden'); else s.setAttribute('aria-hidden', 'true');
			});
			dots.forEach((d, k) => {
				d.classList.toggle('is-active', k === cur);
				if (k === cur) d.setAttribute('aria-current', 'true'); else d.removeAttribute('aria-current');
			});
			if (counter) counter.textContent = fa(cur + 1) + ' / ' + fa(slides.length);
			schedule();
		};
		const schedule = () => {
			clearTimeout(timer);
			if (!userPaused && !hovered && !focused && !document.hidden) timer = setTimeout(() => go(cur + 1), DELAY);
		};
		const setPaused = (p) => {
			userPaused = p;
			root.classList.toggle('is-paused', p);
			pauseBtn?.setAttribute('aria-label', p ? 'ادامه نمایش خودکار' : 'توقف نمایش خودکار');
			schedule();
		};

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
					if (ok) shown++;
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

	/* Payment page: the demo has no bank gateway connected; explain instead of navigating. */
	function initPayButton() {
		const btn = document.querySelector('[data-pay-btn]');
		const note = document.querySelector('[data-pay-note]');
		btn?.addEventListener('click', () => { if (note) note.hidden = false; });
	}

	/* Move focus to a server-side status message (after a form redirect) so screen readers announce it. */
	function initFocusMessages() {
		document.querySelector('[data-focus]')?.focus({ preventScroll: false });
	}

	const modules = [initHeader, initReveal, initSlider, initFilter, initCounters, initDemoForms, initLightbox, initResumeForm, initPayButton, initFocusMessages];
	window.atiyeh = { modules, reduceMotion };
	const boot = () => modules.forEach((fn) => fn());
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
	else boot();
})();
