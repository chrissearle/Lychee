<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

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

// We need that to force https everywhere
//if (env('APP_ENV') === 'production') {

if (env('APP_ENV') === 'dev') {
	URL::forceScheme('https');
}

Route::feeds();

Route::get('/', [IndexController::class, 'show'])->name('home')->middleware('installed');
Route::get('/phpinfo', [IndexController::class, 'phpinfo'])->middleware('admin');
Route::get('/gallery', [IndexController::class, 'gallery'])->name('gallery')->middleware('installed');

/*
 * TODO see to add better redirection functionality later.
 * This is to prevent instagram from taking control our # in url when sharing an album
 * and not consider it as an hash-tag.
 *
 * Other ideas, redirection by album name, photo title...
 */
Route::get('/r/{albumid}/{photoid}', [RedirectController::class, 'photo']);
Route::get('/r/{albumid}', [RedirectController::class, 'album']);

Route::get('/view', [ViewController::class, 'view']);
Route::get('/demo', [DemoController::class, 'js']);
Route::get('/frame', [FrameController::class, 'init'])->name('frame');

Route::post('/php/index.php', [SessionController::class, 'init']); // entry point if options are not initialized

Route::post('/api/Session::init', [SessionController::class, 'init']);
Route::post('/api/Session::login', [SessionController::class, 'login']);
Route::post('/api/Session::logout', [SessionController::class, 'logout']);

Route::post('/api/Albums::get', [AlbumsController::class, 'get']);
Route::post('/api/Albums::getPositionData', [AlbumsController::class, 'getPositionData']);

Route::post('/api/Album::get', [AlbumController::class, 'get'])->middleware('read');
Route::post('/api/Album::getPositionData', [AlbumController::class, 'getPositionData'])->middleware('read');
Route::post('/api/Album::getPublic', [AlbumController::class, 'getPublic']);
Route::post('/api/Album::add', [AlbumController::class, 'add'])->middleware('upload');
Route::post('/api/Album::addByTags', [AlbumController::class, 'addByTags'])->middleware('upload');
Route::post('/api/Album::setTitle', [AlbumController::class, 'setTitle'])->middleware('upload');
Route::post('/api/Album::setDescription', [AlbumController::class, 'setDescription'])->middleware('upload');
Route::post('/api/Album::setShowTags', [AlbumController::class, 'setShowTags'])->middleware('upload');
Route::post('/api/Album::setPublic', [AlbumController::class, 'setPublic'])->middleware('upload');
Route::post('/api/Album::delete', [AlbumController::class, 'delete'])->middleware('upload');
Route::post('/api/Album::merge', [AlbumController::class, 'merge'])->middleware('upload');
Route::post('/api/Album::move', [AlbumController::class, 'move'])->middleware('upload');
Route::post('/api/Album::setLicense', [AlbumController::class, 'setLicense'])->middleware('upload');
Route::get('/api/Album::getArchive', [AlbumController::class, 'getArchive'])->middleware('read');

Route::post('/api/Frame::getSettings', [FrameController::class, 'getSettings']);

Route::post('/api/Photo::get', [PhotoController::class, 'get'])->middleware('read');
Route::post('/api/Photo::getRandom', [PhotoController::class, 'getRandom']);
Route::post('/api/Photo::setTitle', [PhotoController::class, 'setTitle'])->middleware('upload');
Route::post('/api/Photo::setDescription', [PhotoController::class, 'setDescription'])->middleware('upload');
Route::post('/api/Photo::setStar', [PhotoController::class, 'setStar'])->middleware('upload');
Route::post('/api/Photo::setPublic', [PhotoController::class, 'setPublic'])->middleware('upload');
Route::post('/api/Photo::setAlbum', [PhotoController::class, 'setAlbum'])->middleware('upload');
Route::post('/api/Photo::setTags', [PhotoController::class, 'setTags'])->middleware('upload');
Route::post('/api/Photo::add', [PhotoController::class, 'add'])->middleware('upload');
Route::post('/api/Photo::delete', [PhotoController::class, 'delete'])->middleware('upload');
Route::post('/api/Photo::duplicate', [PhotoController::class, 'duplicate'])->middleware('upload');
Route::post('/api/Photo::setLicense', [PhotoController::class, 'setLicense'])->middleware('upload');
Route::get('/api/Photo::getArchive', [PhotoController::class, 'getArchive'])->middleware('read');
Route::get('/api/Photo::clearSymLink', [PhotoController::class, 'clearSymLink'])->middleware('admin');

Route::post('/api/PhotoEditor::rotate', [PhotoEditorController::class, 'rotate'])->middleware('upload');

Route::post('/api/Sharing::List', [SharingController::class, 'listSharing'])->middleware('upload');
Route::post('/api/Sharing::ListUser', [SharingController::class, 'getUserList'])->middleware('upload');
Route::post('/api/Sharing::Add', [SharingController::class, 'add'])->middleware('upload');
Route::post('/api/Sharing::Delete', [SharingController::class, 'delete'])->middleware('upload');

Route::post('/api/Settings::setLogin', [SettingsController::class, 'setLogin']);

Route::post('/api/Import::url', [ImportController::class, 'url'])->middleware('upload');
Route::post('/api/Import::server', [ImportController::class, 'server'])->middleware('admin');

Route::post('/api/User::List', [UserController::class, 'list'])->middleware('upload');
Route::post('/api/User::Save', [UserController::class, 'save'])->middleware('admin');
Route::post('/api/User::Delete', [UserController::class, 'delete'])->middleware('admin');
Route::post('/api/User::Create', [UserController::class, 'create'])->middleware('admin');

Route::post('/api/Logs', [LogController::class, 'display'])->middleware('admin');
Route::post('/api/Logs::clearNoise', [LogController::class, 'clearNoise'])->middleware('admin');
Route::post('/api/Diagnostics', [DiagnosticsController::class, 'get']);
Route::post('/api/Diagnostics::getSize', [DiagnosticsController::class, 'get_size']);

Route::get('/Logs', [LogController::class, 'display'])->middleware('admin');
Route::get('/api/Logs::clear', [LogController::class, 'clear'])->middleware('admin');
Route::get('/Diagnostics', [DiagnosticsController::class, 'show']);

Route::get('/Update', [UpdateController::class, 'apply'])->middleware('admin');
Route::post('/api/Update::Apply', [UpdateController::class, 'apply'])->middleware('admin');
Route::post('/api/Update::Check', [UpdateController::class, 'check'])->middleware('admin');

// unused
Route::post('/api/Logs::clear', [LogController::class, 'clear'])->middleware('admin');

Route::post('/api/search', [SearchController::class, 'search']);

// This route NEEDS to be the last one as it will catch anything else.
Route::get('/{page}', [PageController::class, 'page']);
