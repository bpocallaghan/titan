<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*
|------------------------------------------
| Website
|------------------------------------------
*/
Route::redirect('/home', '/');
Route::group(['namespace' => 'Website'], function () {
    //Route::get('/', 'HomeController@index');
});

/*
|------------------------------------------
| Authenticate User
|------------------------------------------
*/
if (in_array('auth', config('titan.routes'))) {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        // logout (get or post)
        Route::any('logout', 'LoginController@logout')->name('logout');

        Route::group(['middleware' => 'guest'], function () {
            // login
            Route::get('login', 'LoginController@showLoginForm')->name('login');
            Route::post('login', 'LoginController@login');

            // registration
            Route::get('register/{token?}', 'RegisterController@showRegistrationForm')
                ->name('register');
            Route::post('register', 'RegisterController@register');
            Route::get('register/confirm/{token}', 'RegisterController@confirmAccount');

            // password reset
            Route::get('password/forgot', 'ForgotPasswordController@showLinkRequestForm')
                ->name('forgot-password');
            Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
            Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')
                ->name('password.reset');
            Route::post('password/reset', 'ResetPasswordController@reset');
        });
    });
}

/*
|------------------------------------------
| Admin (when authorized and admin)
|------------------------------------------
*/
Route::group(['middleware' => ['auth', 'auth.admin'], 'prefix' => 'admin', 'namespace' => 'Admin'],
    function () {
        Route::get('/', 'DashboardController@index')->name('admin');

        // profile
        Route::get('/profile', 'ProfileController@index');
        Route::put('/profile/{user}', 'ProfileController@update');

        // analytics
        Route::group(['prefix' => 'analytics'], function () {
            Route::get('/', 'AnalyticsController@summary');
            Route::get('/devices', 'AnalyticsController@devices');
            Route::get('/visits-and-referrals', 'AnalyticsController@visitsReferrals');
            Route::get('/interests', 'AnalyticsController@interests');
            Route::get('/demographics', 'AnalyticsController@demographics');
        });

        // history
        Route::group(['prefix' => 'latest-activity', 'namespace' => 'History'], function () {
            Route::get('/', 'HistoryController@website');
            Route::get('/admin', 'HistoryController@admin');
            Route::get('/website', 'HistoryController@website');
        });

        // general
        Route::group(['prefix' => 'general', 'namespace' => 'General'], function () {
            Route::resource('tags', 'TagsController');

            Route::get('/banners/order', 'BannersOrderController@index');
            Route::post('/banners/order', 'BannersOrderController@update');
            Route::resource('banners', 'BannersController');

            // testimonials
            Route::group(['namespace' => 'Testimonials'], function () {
                Route::get('testimonials/order', 'OrderController@index');
                Route::post('testimonials/order', 'OrderController@updateOrder');
                Route::resource('testimonials', 'TestimonialsController');
            });

            // faq
            Route::group(['namespace' => 'FAQ'], function () {
                Route::resource('/faqs/categories', 'CategoriesController');
                Route::get('faqs/order', 'OrderController@index');
                Route::post('faqs/order', 'OrderController@updateOrder');
                Route::resource('/faqs', 'FAQsController');
            });
        });

        // pages order
        Route::group(['prefix' => 'pages', 'namespace' => 'Pages'], function () {
            Route::get('/order/{type?}', 'OrderController@index');
            Route::post('/order/{type?}', 'OrderController@updateOrder');

            // manage page sections list order
            Route::get('/{page}/sections', 'PageContentController@index');
            Route::post('/{page}/sections/order', 'PageContentController@updateOrder');
            Route::delete('/{page}/sections/{section}', 'PageContentController@destroy');

            // page components
            Route::resource('/{page}/sections/content', 'PageContentController');
            //remove content media
            Route::post('/{page}/sections/content/{content}/removeMedia', 'PageContentController@removeMedia');

        });
        Route::resource('pages', 'Pages\PagesController');
        // order page content photos
        Route::get('/pages/{page}/sections/content/{content}/edit/order', 'Photos\PhotosOrderController@showPageContentPhotos');
        Route::get('/pages/{page}/sections/content/{content}/edit/videos/order', 'Photos\VideosOrderController@showPageContentVideos');

        // blog
        Route::group(['prefix' => 'blog', 'namespace' => 'Blog'], function () {
            Route::redirect('/', '/admin/blog/articles');
            Route::resource('categories', 'CategoriesController');
            Route::resource('articles', 'ArticlesController');
        });

        // news and events
        Route::group(['prefix' => 'news-and-events', 'namespace' => 'NewsEvents'], function () {
            Route::resource('news', 'NewsController');
            Route::resource('categories', 'CategoriesController');
        });

        // shop / products
        Route::group(['prefix' => 'shop', 'namespace' => 'Shop'], function () {
            Route::get('categories/order', 'CategoriesOrderController@index');
            Route::post('categories/order', 'CategoriesOrderController@updateListOrder');
            Route::resource('categories', 'CategoriesController');
            Route::resource('products', 'ProductsController');
            Route::resource('status', 'StatusesController');

            Route::get('checkouts', 'CheckoutsController@index');
            Route::get('checkouts/{checkout}', 'CheckoutsController@show');
            Route::get('transactions', 'TransactionsController@index');
            Route::get('transactions/{transaction}', 'TransactionsController@show');
            Route::get('transactions/{transaction}/print/{format?}', 'TransactionsController@printOrder');
            Route::post('transactions/{transaction}/status', 'TransactionsController@updateStatus');
        });

        // gallery / photos
        Route::group(['prefix' => 'photos', 'namespace' => 'Photos'], function () {
            Route::get('/', 'PhotosController@index');
            Route::delete('/{photo}', 'PhotosController@destroy');
            Route::post('/upload', 'PhotosController@uploadPhotos');
            Route::post('/{photo}/edit/name', 'PhotosController@updatePhotoName');
            Route::post('/{photo}/cover', 'PhotosController@updatePhotoCover');

            // photoables
            Route::get('/news/{news}', 'PhotosController@showNewsPhotos');
            Route::get('/articles/{article}', 'PhotosController@showArticlePhotos');
            Route::get('/products/{product}', 'PhotosController@showProductPhotos');

            Route::resource('/albums', 'AlbumsController', ['except' => 'show']);
            Route::get('/albums/{album}', 'PhotosController@showAlbumPhotos');

            //photos order
            Route::get('/news/{news}/order', 'PhotosOrderController@showNewsPhotos');
            Route::get('/articles/{article}/order', 'PhotosOrderController@showArticlePhotos');
            Route::get('/albums/{album}/order', 'PhotosOrderController@showAlbumPhotos');
            Route::post('/order', 'PhotosOrderController@update');

            // croppers
            Route::post('/crop/{photo}', 'CropperController@cropPhoto');
            Route::get('/news/{news}/crop/{photo}', 'CropperController@showNewsPhoto');
            Route::get('/albums/{album}/crop/{photo}', 'CropperController@showAlbumsPhoto');
            Route::get('/products/{product}/crop/{photo}', 'CropperController@showProductPhoto');
            Route::get('/articles/{article}/crop/{photo}', 'CropperController@showArticlesPhoto');

            // resource image crop
            Route::post('/crop-resource', 'CropResourceController@cropPhoto');
            Route::get('/banners/{banner}/crop-resource/', 'CropResourceController@showBanner');

            //videos
            Route::resource('/albums/{album}/videos', 'VideosController', ['except' => 'show']);

            Route::get('/videos', 'VideosController@index');
            Route::post('/videos/{video}/getInfo', 'VideosController@videoInfo');
            Route::post('/videos/{video}/cover', 'VideosController@updateVideoCover');
            Route::post('/videos/create', 'VideosController@store');
            Route::post('/videos/{video}/edit', 'VideosController@update');
            Route::delete('/videos/{video}', 'VideosController@destroy');

            //videos order
            Route::get('/news/{news}/videos/order', 'VideosOrderController@showNewsVideos');
            Route::get('/articles/{article}/videos/order', 'VideosOrderController@showArticleVideos');
            Route::get('/albums/{album}/videos/order', 'VideosOrderController@showAlbumVideos');
            Route::post('/videos/order', 'VideosOrderController@update');
        });

        // accounts
        Route::group(['prefix' => 'accounts', 'namespace' => 'Accounts'], function () {
            // clients
            Route::post('clients/filter', 'ClientsController@filter');
            Route::resource('clients', 'ClientsController')->parameters([
                'clients' => 'user'
            ]);
            Route::post('clients/{user}/notify/forgot-password',
                'ClientsController@sendResetLinkEmail');

            // users
            Route::get('administrators/invites', 'AdministratorsController@showInvites');
            Route::post('administrators/invites', 'AdministratorsController@postInvite');
            Route::resource('administrators', 'AdministratorsController');
        });

        // corporate
        Route::group(['prefix' => 'newsletter', 'namespace' => 'Newsletter'], function () {
            Route::resource('subscribers', 'SubscribersController');
        });

        // documents
        Route::group(['prefix' => 'documents', 'namespace' => 'Documents'], function () {
            // documents
            Route::get('/', 'DocumentsController@index');
            Route::delete('/{document}', 'DocumentsController@destroy');
            Route::post('/upload', 'DocumentsController@upload');
            Route::post('/{document}/edit/name', 'DocumentsController@updateName');

            // documentable
            Route::get('/category/{category}', 'DocumentsController@showCategory');

            // categories
            Route::resource('/categories', 'CategoriesController');
        });

        // reports
        Route::group(['prefix' => 'reports', 'namespace' => 'Reports'], function () {
            Route::get('summary', 'SummaryController@index');

            // feedback contact us
            Route::get('contact-us', 'ContactUsController@index');
            Route::post('contact-us/chart', 'ContactUsController@getChartData');
            Route::get('contact-us/datatable', 'ContactUsController@getTableData');
        });

        Route::group(['prefix' => 'settings', 'namespace' => 'Settings'], function () {
            Route::resource('roles', 'RolesController');

            // settings
            Route::resource('settings', 'SettingsController');

            // navigation
            Route::get('navigation/order', 'NavigationOrderController@index');
            Route::post('navigation/order', 'NavigationOrderController@updateOrder');
            Route::get('navigation/datatable', 'NavigationController@getTableData');
            Route::resource('navigation', 'NavigationController');

            // locations
            Route::group(['prefix' => 'locations', 'namespace' => 'Locations'], function () {
                Route::resource('cities', 'CitiesController');
                Route::resource('suburbs', 'SuburbsController');
                Route::resource('provinces', 'ProvincesController');
                Route::resource('countries', 'CountriesController');
                Route::resource('continents', 'ContinentsController');
            });
        });
    });

/*
|--------------------------------------------------------------------------
| AJAX ROUTES
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax', 'middleware' => 'web'], function () {
    // logs
    Route::group(['prefix' => 'log'], function () {
        Route::post('social-media', 'LogsController@socialMedia');
    });
});
