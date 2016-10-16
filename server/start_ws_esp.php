<?php
/** 服务端业务逻辑控制器 */
require('WebSocket.class.php');

$ws = new Ws('192.168.1.103', '8080', 1000);
$ws->function['add']   = 'add_callback';
$ws->function['send']  = 'send_callback';
$ws->function['close'] = 'close_callback';
$ws->start_server();

//回调函数们
function add_callback($ws) {
    $number = count($ws->accept);
    //send_to_all('add-num:'.$number, $ws); // 更新在线用户数
}

function close_callback($ws) {
    $data = count($ws->accept);
    //send_to_all('num: '.$data, $ws); // 更新在线用户数
}

function send_callback($data, $index, $ws) {
    // $to_user_index = -1;
    // if(strpos($data, ',') === false){
    //     $msg = $data;
    // }else{
    //     list($msg, $to_user_index) = explode(',', $data);
    // }


    // $data = json_encode(array(
    //     'text' => $msg,
    //     'from_user' => $index, // from accept index
    // ));

    // if($to_user_index >= 0){
    //     send_to_single($to_user_index, $data, 'text', $ws);
    //     send_to_single($index, $data, 'text', $ws);
    // }else{
    print_r($data."\r\n");
        send_to_all($data, $ws);

    // }
}

function send_to_all($data, $ws){
    $res = array(
        'type' => 'text',
        'msg'  => $data,
    );
    print_r($data."\r\n");
    $res = json_encode($res);
    $res = $ws->frame($res);
    foreach ($ws->accept as $key => $accept) {
        print_r($accept);
        socket_write($accept, $res, strlen($res));
    }
}

// function send_to_single($index, $data, $type, $ws){
//     $response = array(
//         'type' => $type,
//         'msg'  => $data,
//     );
//     $response = json_encode($response);
//     $response = $ws->frame($response);
//     socket_write($ws->accept[$index], $response, strlen($response));
// }


