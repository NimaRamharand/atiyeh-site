<?php
/**
 * Sample copy for the inner pages (about, services, company, team, licenses, org chart, careers, contact).
 *
 * The descriptive sentences are demo copy written for the two services; company facts we do not have
 * (names, years, licence numbers, figures, address) stay as «[ … ]» placeholders.
 * Page intros come from each page's own editor content, so the company can rewrite them in WordPress.
 *
 * @package Atiyeh
 */

/** Core values (about page). */
function atiyeh_values(): array {
	return array(
		array( 'target', 'دقت در جزئیات', 'هر پلاک و هر سند با کنترل کیفیت چندمرحله‌ای بررسی می‌شود تا اطلاعات نادرست به دست کارفرما نرسد.' ),
		array( 'award', 'تعهد به زمان‌بندی', 'برنامه اجرای هر پروژه پیش از شروع با کارفرما تنظیم می‌شود و گزارش پیشرفت به‌صورت منظم ارائه می‌شود.' ),
		array( 'shield', 'امنیت اطلاعات', 'اسناد و داده‌های سازمان‌ها در فرایندی کنترل‌شده نگهداری می‌شوند و دسترسی به آن‌ها محدود و ثبت‌شده است.' ),
		array( 'users', 'نیروی آموزش‌دیده', 'تیم‌های میدانی و اپراتورهای اسکن پیش از شروع هر پروژه آموزش می‌بینند و با دستورالعمل واحد کار می‌کنند.' ),
	);
}

/** Why-us reasons (company page). */
function atiyeh_reasons(): array {
	return array(
		array( 'layers', 'تمرکز بر دو تخصص', 'به‌جای پراکندگی، تمام تجربه و تجهیزات خود را روی پلاک کدپستی و آرشیو دیجیتال متمرکز کرده‌ایم.' ),
		array( 'pin', 'اجرای میدانی منظم', 'برداشت، نصب و بهنگام‌سازی نشانی‌ها با برنامه محله‌به‌محله و ثبت دقیق موقعیت انجام می‌شود.' ),
		array( 'doc', 'اسکن با کیفیت بالا', 'اسناد با تنظیمات مناسب هر نوع کاغذ اسکن می‌شوند تا خوانا و قابل استناد باقی بمانند.' ),
		array( 'chart', 'گزارش‌دهی شفاف', 'کارفرما در هر مرحله از پیشرفت کار، حجم انجام‌شده و موارد نیازمند پیگیری مطلع است.' ),
		array( 'shield', 'حفاظت از داده', 'فرایندهای کار با اسناد محرمانه، از تحویل تا بازگرداندن نسخه کاغذی، کنترل‌شده است.' ),
		array( 'clock', 'پشتیبانی پس از تحویل', 'پس از تحویل پروژه، برای رفع اشکال و پاسخ به پرسش‌های کارفرما در دسترس هستیم.' ),
	);
}

/** Service detail blocks (services page). Features reuse the 8 activity areas. */
function atiyeh_service_blocks(): array {
	$areas = atiyeh_home_areas();
	return array(
		array(
			'id'       => 'plaque',
			'badge'    => 'خدمت ۱',
			'title'    => 'پلاک کدپستی',
			'lead'     => 'از طراحی و تولید پلاک تا نصب میدانی و بهنگام‌سازی کدپستی؛ تمام مراحل با یک تیم و یک استاندارد انجام می‌شود.',
			'text'     => 'پلاک‌ها با مواد مقاوم در برابر نور خورشید و شرایط جوی ساخته می‌شوند و نیروهای میدانی پس از تطبیق نشانی با اطلاعات کدپستی، آن‌ها را بر سر در ساختمان‌ها نصب می‌کنند. اطلاعات هر نصب ثبت و پس از بازرسی کنترل کیفیت تأیید می‌شود.',
			'img'      => 'project-postal-plaque.webp',
			'icons'    => array( 'plaque', 'pin', 'growth', 'check' ),
			'features' => array_slice( $areas, 0, 4 ),
		),
		array(
			'id'       => 'archive',
			'badge'    => 'خدمت ۲',
			'title'    => 'آرشیو دیجیتال اسناد',
			'lead'     => 'اسناد کاغذی سازمان شما به آرشیوی دیجیتال، منظم و قابل جستجو تبدیل می‌شود؛ بدون دغدغه فضا و فرسودگی کاغذ.',
			'text'     => 'پرونده‌ها پس از آماده‌سازی و رفع منگنه، با اسکنرهای مناسب حجم بالا اسکن می‌شوند. سپس مشخصات هر سند نمایه‌سازی می‌شود تا در سامانه بازیابی با چند کلیک پیدا شود و نسخه پشتیبان آن به‌صورت امن نگهداری شود.',
			'img'      => 'careers-team.webp',
			'icons'    => array( 'doc', 'layers', 'search', 'shield' ),
			'features' => array_slice( $areas, 4, 4 ),
		),
	);
}

/** FAQ (services page). */
function atiyeh_faq(): array {
	return array(
		array( 'برای شروع همکاری چه اطلاعاتی لازم است؟', 'کافی است فرم درخواست خدمات را تکمیل کنید. کارشناسان ما برای دریافت جزئیات پروژه، مانند محدوده اجرا یا حجم تقریبی اسناد، با شما تماس می‌گیرند.' ),
		array( 'آیا امکان اجرای پروژه در شهرهای مختلف وجود دارد؟', 'بله. تیم‌های میدانی بر اساس برنامه‌ای که با کارفرما تنظیم می‌شود به محل پروژه اعزام می‌شوند.' ),
		array( 'اسناد در طول اسکن کجا نگهداری می‌شوند؟', 'بسته به نیاز سازمان، اسکن در محل کارفرما یا در مرکز اسکن شرکت انجام می‌شود و تحویل و بازگرداندن اسناد صورت‌جلسه می‌شود.' ),
		array( 'خروجی آرشیو دیجیتال به چه شکل تحویل داده می‌شود؟', 'فایل‌های اسکن‌شده همراه با اطلاعات نمایه هر سند تحویل می‌شوند و از طریق سامانه بازیابی قابل جستجو هستند.' ),
		array( 'هزینه پروژه چگونه برآورد می‌شود؟', 'هزینه بر اساس تعداد پلاک یا حجم اسناد و شرایط اجرا برآورد می‌شود و پیش از شروع کار به‌صورت مکتوب به کارفرما ارائه می‌شود.' ),
	);
}

/** Careers perks and hiring steps. */
function atiyeh_perks(): array {
	return array(
		array( 'users', 'کار در تیمی منسجم', 'در کنار نیروهای باتجربه، روی پروژه‌های شهری و سازمانی کار کنید.' ),
		array( 'growth', 'آموزش و رشد', 'پیش از شروع هر پروژه آموزش‌های لازم را می‌بینید و مسیر پیشرفت شغلی روشن است.' ),
		array( 'clock', 'برنامه کاری مشخص', 'ساعات و محل کار برای هر پروژه از قبل مشخص و اعلام می‌شود.' ),
		array( 'spark', 'کار اثرگذار', 'نتیجه کار شما در نظم نشانی‌های شهر و ماندگاری اسناد دیده می‌شود.' ),
	);
}

/** Hiring steps. */
function atiyeh_hiring_steps(): array {
	return array(
		array( '۱', 'ارسال رزومه', 'فرم پایین همین صفحه را تکمیل و رزومه را پیوست کنید.' ),
		array( '۲', 'بررسی رزومه', 'رزومه شما توسط واحد منابع انسانی بررسی می‌شود.' ),
		array( '۳', 'مصاحبه', 'در صورت تناسب، برای مصاحبه با شما تماس می‌گیریم.' ),
		array( '۴', 'شروع همکاری', 'پس از دوره آموزشی، به تیم پروژه می‌پیوندید.' ),
	);
}

/** What happens after a service request. */
function atiyeh_request_steps(): array {
	return array(
		array( 'mail', 'دریافت درخواست', 'درخواست شما ثبت و به کارشناس مربوط ارجاع می‌شود.' ),
		array( 'phone', 'تماس کارشناس', 'برای دریافت جزئیات و بررسی نیاز با شما تماس می‌گیریم.' ),
		array( 'doc', 'ارائه پیشنهاد', 'برنامه اجرا و برآورد هزینه به‌صورت مکتوب ارسال می‌شود.' ),
	);
}

/** The four «درباره ما» sub-pages: slug => [title, icon, description]. */
function atiyeh_about_children(): array {
	return array(
		'company'    => array( 'معرفی شرکت', 'spark', 'داستان، رسالت و دلایل انتخاب ما.' ),
		'management' => array( 'تیم مدیریت', 'users', 'مدیرانی که پروژه‌ها را هدایت می‌کنند.' ),
		'licenses'   => array( 'مجوزها و رتبه‌بندی‌ها', 'award', 'گواهی‌ها و صلاحیت‌های حرفه‌ای شرکت.' ),
		'org-chart'  => array( 'چارت سازمانی', 'layers', 'ساختار واحدها و مسیر هماهنگی پروژه‌ها.' ),
	);
}

/** Org chart (sample structure until the company provides its own). */
function atiyeh_org_chart(): array {
	return array(
		'top'   => array( 'هیئت مدیره', '[ نام اعضا ]' ),
		'ceo'   => array( 'مدیرعامل', '[ نام و نام خانوادگی ]' ),
		'units' => array(
			array( 'واحد پلاک کدپستی', array( 'تولید پلاک', 'نصب میدانی', 'بهنگام‌سازی' ) ),
			array( 'واحد آرشیو دیجیتال', array( 'اسکن اسناد', 'نمایه‌سازی', 'بازیابی و پشتیبان' ) ),
			array( 'واحد کنترل کیفیت', array( 'بازرسی میدانی', 'کنترل داده' ) ),
			array( 'واحد اداری و مالی', array( 'منابع انسانی', 'امور مالی' ) ),
		),
	);
}

/** Intro text of a page from its editor content, ignoring the old «[ … تکمیل می‌شود ]» placeholder. */
function atiyeh_page_intro(): string {
	$raw = trim( wp_strip_all_tags( get_the_content() ) );
	if ( '' === $raw || str_contains( $raw, 'توسط شرکت تکمیل می‌شود' ) ) {
		return '';
	}
	return (string) apply_filters( 'the_content', get_the_content() );
}
