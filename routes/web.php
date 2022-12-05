<?php

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

use App\Models\Task;
use Illuminate\Http\Request;

// GENERAL
Route::get('/home', function () {
    return view('tasks', [
        'tasks' => Task::orderBy('created_at', 'asc')->get()
    ]);
})->middleware('auth');

Route::get('/', function () {
    error_log("INFO: get /");
    return view('tasks', [
        'tasks' => Task::orderBy('created_at', 'asc')->get()
    ]);
})->middleware('auth');

// ADD TASK
Route::post('/task', function (Request $request) {
    error_log("INFO: post /task");
    $validator = Validator::make($request->all(), [
        'orderid' => 'required|max:255',
    ]);

    if ($validator->fails()) {
        error_log("ERROR: Add task failed.");
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    $task = new Task;
    $task->orderid = $request->orderid;
    $task->milling = $request->milling;
    $task->drilling = $request->drilling;
    $task->plant = $request->plant;           
    $task->save();

    //sending over Azure IoT Hub REST API
    $curl = curl_init();

    $message = new stdClass();
    $message->orderid = $request->orderid;
    $message->milling = $request->milling;
    $message->drilling = $request->drilling;
    $message->plant = $request->plant;    
    $payload = json_encode($message);
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iotHubDeNayer.azure-devices.net/devices/Webapp/messages/events?api-version=2018-06-30",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => array(
            "authorization: SharedAccessSignature sr=iotHubDeNayer.azure-devices.net%2Fdevices%2FWebapp&sig=42HeIKhy%2FnS5%2F4svqcpDpU2lyii90lwca4d%2FsH3JZeY%3D&se=1670001558",
        ),
    ));
    echo "Sending message: " . $payload . "\n";
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($err) {
        echo $err;
        exit(1);
    } else {
        echo "IoT Hub responded to message with status: " . $httpcode . "\n";
    }
    
    curl_close($curl);

    return redirect('/');
})->middleware('auth');

// DELETE TASK
Route::delete('/task/{id}', function ($id) {
    error_log('INFO: delete /task/'.$id);
    Task::findOrFail($id)->delete();

    return redirect('/');
})->middleware('auth');


Auth::routes();



