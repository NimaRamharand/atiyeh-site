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

	const modules = [initHeader, initReveal];
	window.atiyeh = { modules, reduceMotion };
	const boot = () => modules.forEach((fn) => fn());
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
	else boot();
})();
