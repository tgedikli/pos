<?php

require '../../../vendor/autoload.php';

$host_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]";
$path = '/pos/examples/ykb/regular/';
$base_url = $host_url . $path;

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
$ip = $request->getClientIp();

$account = [
    'bank'          => 'yapikredi',
    'model'         => 'regular',
    'client_id'     => '6706598320',
    'terminal_id'   => '67322946',
    'posnet_id'     => '27426',
    'env'           => 'test',
];

$template_title = 'Post Auth Order';

require '../../template/_header.php';

try {
    $pos = new \Mews\Pos\Pos($account);
} catch (\Mews\Pos\Exceptions\BankNotFoundException $e) {
    var_dump($e->getCode(), $e->getMessage());
} catch (\Mews\Pos\Exceptions\BankClassNullException $e) {
    var_dump($e->getCode(), $e->getMessage());
}

$order = [
    'id'            => '2018102949E0',
    'host_ref_num'  => '018711533790000181',
    'amount'        => '100',
    'currency'      => 'TRY',
    'installment'   => '2',
    'transaction'   => 'post',
];

try {
    $pos->prepare($order);
} catch (\Mews\Pos\Exceptions\UnsupportedTransactionTypeException $e) {
    var_dump($e->getCode(), $e->getMessage());
}

$payment = $pos->payment();

$response = $payment->response;
$dump = get_object_vars($response);
?>

<div class="result">
    <h3 class="text-center text-<?php echo $pos->isSuccess() ? 'success' : 'danger'; ?>">
        <?php echo $pos->isSuccess() ? 'Post Auth Order is successful!' : 'Post Auth Order is not successful!'; ?>
    </h3>
    <dl class="row">
        <dt class="col-sm-12">All Data Dump:</dt>
        <dd class="col-sm-12">
            <pre><?php print_r($dump); ?></pre>
        </dd>
    </dl>
    <hr>
    <div class="text-right">
        <a href="index.php" class="btn btn-lg btn-info">&lt; Click to payment form</a>
    </div>
</div>

<?php require '../../template/_footer.php'; ?>
