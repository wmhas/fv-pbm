<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/pages/samples/blank-page.html', function () {
    return view('pages.samples.blank-page');
});

Auth::routes();
Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');

    Route::group(['prefix' => 'home'], function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/search/patient', 'HomeController@search_patient');
        Route::get('/search/order', 'HomeController@search_order');
        Route::get('/order', 'HomeController@view_order');
        Route::get('/report', 'HomeController@see_more_refill');
        Route::get('/order_end', 'HomeController@see_more_end');
    });

    Route::group(['prefix' => 'patient'], function () {
        Route::get('/', 'PatientController@index')->name('patient');
        Route::get('/{patient}/detail', 'PatientController@patient_detail'); //utk ui update
        Route::get('/{patient}/card-update', 'PatientController@update_card_owner');
        Route::post('/{patient}/card-store', 'PatientController@store_card_owner');
        Route::post('/{patient}/update', 'PatientController@update_patient'); //utk update
        Route::post('/{patient}/updateIcAttachment', 'PatientController@update_ic_attach');
        Route::post('/{attachment}/deleteAttachment', 'PatientController@deleteAttachment');
        Route::get('/{patient}/view', 'PatientController@patient_view'); //utk view
        Route::get('/create/{patient?}', 'PatientController@create');
        Route::get('/create-address/{id}', 'PatientController@create_address');
        Route::get('/create-card/{id}', 'PatientController@create_card');
        Route::post('/store', 'PatientController@store');
        Route::post('/{patient}/store/address', 'PatientController@store_address');
        Route::post('/{patient}/store/card', 'PatientController@store_card');
        Route::get('/search', 'PatientController@search');
        Route::get('/view', 'PatientController@show');
        Route::get('/{patient}/view', 'PatientController@patient_view');
        Route::get('/{id}/view/downloadICAttachment', 'PatientController@downloadICAttachment');
        Route::get('/{id}/view/downloadSLAttachment', 'PatientController@downloadSLAttachment');
        Route::get('/print', 'PatientController@print_jhev');
        Route::get('/downloadPDF1/{id}', 'PatientController@download_jhev');
        Route::get('/pdf', 'PatientController@pdf');

        Route::get('/create-relation/{patient}', 'PatientController@register_relation');
        Route::post('/create-relation/{patient}', 'PatientController@store_relation');
        Route::post('/delete', 'PatientController@delete_old')->name("patient.delete_old"); //utk update
        Route::post('/delete', 'PatientController@delete')->name("patient.delete");
    });

    Route::group(['prefix' => 'item'], function () {
        Route::get('/index', 'ItemController@index');
        Route::get('/search', 'ItemController@search');
        Route::get('/{item}/view', 'ItemController@view');
        Route::get('/create', 'ItemController@create');
        Route::post('/create/save', 'ItemController@store_create');
        Route::get('/{item}/update', 'ItemController@edit');
        Route::post('/{item}/update/save', 'ItemController@store_edit');
    });

    Route::group(['prefix' => 'order'], function () {
        Route::post('/', 'OrderController@index');
        Route::get('/', 'OrderController@index')->name('orders.index');
        Route::get('/{id}/delete', 'OrderController@destroy')->name('order.delete');
        Route::get('/search', 'OrderController@search');
        Route::get('/{order}/view', 'OrderController@show');
        Route::post('/{order}/OrderAttachment', 'OrderController@uploadOrderAttachment');
        Route::get('/{order}/view/OrderAttachment', 'OrderController@downloadOrderAttachment');
        Route::post('/{order}/update/OrderAttachment', 'OrderController@updateOrderAttachment');
        Route::get('/{patient}/history', 'OrderController@history');
        Route::get('/{patient}/create/{order_id?}', 'OrderController@create_order')->name('order.create');
        Route::post('/{id}/store/{order_id}/dispense', 'OrderController@store_dispense');
        Route::get('/{id}/store/{order_id}/prescription', 'OrderController@create_prescription');
        Route::post('/{id}/store/{order_id}/prescription', 'OrderController@store_prescription');
        Route::get('/{id}/store/{order_id}/orderentry', 'OrderController@create_orderEntry')->name('order.entry');
        Route::post('/{id}/store/{order_id}/orderentry', 'OrderController@store_orderEntry');
        Route::post('/store_item', 'OrderController@store_item');
        Route::post('/store_item_resubmission', 'OrderController@store_item_resubmission');
        Route::post('/update_item', 'OrderController@update_item');
        Route::delete('/delete_item/{patient}/{id}', 'OrderController@delete_item');
        Route::get('/{id}/view/downloadConsignmentNote', 'OrderController@downloadConsignmentNote');
        Route::post('/{id}/updateConsignmentNote', 'OrderController@updateConsignmentNote');
        Route::get('/{id}/view/downloadRXAttachment', 'OrderController@downloadRXAttachment')->name('order.rxattachment');
        Route::post('/{id}/updateRXAttachment', 'OrderController@updateRXAttachment');
        Route::post('/{order}/deleteOrder', 'OrderController@deleteOrder');
        Route::get('/{order}/update', 'OrderController@edit')->name('order.update');
        Route::post('/{order}/update', 'OrderController@store_edit');
        Route::post('/{order}/dispense_order','OrderController@dispense_order');
        Route::post('/{order}/complete_order','OrderController@complete_order');
        Route::post('/{order}/return_order','OrderController@return_order');
        Route::delete('/{order}/{order_id}/return','OrderController@return_order_item');
        Route::post('/{order}/resubmission', 'OrderController@resubmission');
        Route::get('/{order}/new_resubmission', 'OrderController@new_resubmission');
        Route::get('/downloadPDF2/{id}', 'OrderController@download_invoice');
        Route::get('/downloadPDF3/{id}', 'OrderController@download_do');
        Route::get('/downloadPDF4/{id}', 'OrderController@download_justify');
        Route::post('/{order}/delivery', 'OrderController@delivery_status');
        Route::get('/date/{do_number}', 'OrderController@date_change')->name('date.change');
        Route::patch('/date/{order}', 'OrderController@date_update')->name('date.update');
    });
    //report
    Route::group(['prefix' => 'report'], function () {
        Route::get('/report_sales', 'ReportController@report_sales');
        Route::get('/search/report_sales', 'ReportController@search_sales');
        Route::get('/report_refill', 'ReportController@report_refill');
        Route::get('/report_item', 'ReportController@report_item');
        Route::get('/report_item_export', 'ReportController@report_item_export');
        Route::get('/report_stocks', 'ReportController@report_stocks');
        Route::get('/{item}/item_summary', 'ReportController@item_summary');
        Route::get('/exportsalesitem', 'ReportController@export_sales_item');
        Route::get('/sales_report', 'ReportController@sales_report');
        Route::get('/exportsalesitemexcel', 'ReportController@export_sales_item_excel')->name('export.sales-item.excel');
        Route::get('/exportstockitem', 'ReportController@export_stock_item')->name('export.stock-item');
        Route::get('/sales_report/queue', 'ReportController@sales_report_queue')->name('sales_report.queue');
    });

    //batch
    Route::group(['prefix' => 'batch'], function () {
        Route::get('/', 'BatchController@index')->name('batch');
        Route::get('/view', 'BatchController@show');
        Route::get('/pending', 'BatchController@pending');
        Route::post('/{order}/batch_order','BatchController@batch_order');
        Route::get('/{batch}/batch_list', 'BatchController@show_batch');
        Route::post('/{batch}/batch_list', 'BatchController@changeStatus');
        Route::get('/search/batched', 'BatchController@search_batch');
        Route::post('/export-excel', 'BatchController@export_batch_excel')->name('batch.export.excel');
        Route::get('/{batch}/delete_batch', 'BatchController@delete_batch')->name('batch.delete');
        Route::get('/{batch}/remove_order/{order}', 'BatchController@remove_order')->name('batch.remove');
    });

    // AJAX
    // Route::get('/getDetails/{id}', 'OrderController@getDetails');
    Route::get('/getItemDetails/{id}', 'AjaxController@getItemDetails');
    Route::get('/getPatients/{id}', 'PatientController@getPatients');
    Route::get('/getPurchase/{id}', 'PurchaseController@getDetails');


    //Hospital
    Route::group(['prefix' => 'hospital'], function () {
        Route::get('/index', 'HospitalController@index');
        Route::post('/index', 'HospitalController@store');
        Route::post('/{hospital}/update', 'HospitalController@update');
        Route::delete('/{hospital}/delete', 'HospitalController@destroy');
        Route::get('/search', 'HospitalController@search');
    });

    //Sticker
    Route::group(['prefix' => 'sticker'], function () {
        Route::get('/', 'StickerController@index')->name('sticker.index');
        Route::get('/{orderId}/print', 'StickerController@print')->name('sticker.print');
        Route::post('/download', 'StickerController@download')->name('sticker.download');
        Route::post('/clear-queue', 'StickerController@clearQueue')->name('sticker.clear-queue');
        // Route::get('/{order_id?}', 'StickerController@index')->name('sticker.index');
        // Route::post('/delete', 'StickerController@delete')->name('sticker.delete');
    });

    // Move Items
    Route::group(['prefix' => 'location'], function () {
        Route::get('/', 'LocationController@index')->name('location.index');
        Route::post('/edit/{item_id}/{on_hand}', 'LocationController@edit')->name('location.edit');
        // Route::get('/add', 'LocationController@add_location');
    });

    // Ajax
    Route::group(['prefix' => 'ajax'], function () {
        Route::get('/getDONumber/{dispensing_by}', 'AjaxController@getDONumber')->name('ajax.getDONumber');
    });

    //Purchase
    Route::group(['prefix' => 'purchase'], function () {
        Route::get('/', 'PurchaseController@index')->name('purchase.index');
        Route::get('/search', 'PurchaseController@search');
        Route::get('/{item}/create_purchase', 'PurchaseController@create_purchase');
        Route::post('/store_purchase', 'PurchaseController@store_purchase');
        Route::get('/history', 'PurchaseController@history')->name('purchase.history');
    });

    // Borang Jev
    Route::group(['prefix' => 'borang'], function () {
        Route::get('/', 'BorangJevController@index')->name('borang.index');
        Route::get('/print', 'BorangJevController@print')->name('borang.print');
        Route::get('/{do_number}/print', 'BorangJevController@print')->name('borangjhev.print');

    });
});
