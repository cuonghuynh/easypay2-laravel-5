**EasyPay V.2 Payment for Laravel 5**

## Laravel

After updating composer, add the PackageServiceProvider to the providers array in `config/app.php`

    CuongHuynh\EasyPay2\PackageServiceProvider::class,

You can optionally use the facade for shorter code. Add this to your facades:

    'EasyPay2' => CuongHuynh\EasyPay2\Facades\EasyPay2Facade::class

Publish the config-file.

    php artisan vendor:publish

You will have `easypay2.php` in config folder


## Usage


### <a name="workflow-image"></a>EasyPay Flow

![workshop image](https://lh4.googleusercontent.com/-MGXxXHzYbRQ/V3kcB4JPfkI/AAAAAAAAaUo/pd5e5eu9JaU4nRLJ-6BzKCZbBTW3KvxawCL0B/w788-h548-no/EasyPay2Flow.png)

### <a name="settings"></a>Settings

Switch to `sandbox` mode by set to `true` in `config/easypay2.php`

    return [
        'sandbox_flag' => true,
        
        'sandbox' => [
            'endpoint' => 'https://test.wirecard.com.sg/easypay2/paymentpage.do?',
            'mid' => 'xxx',
            'security_key' => 'xxx',
            'security_seq' => 'xxx',
        ],
    
        'live' => [
            'endpoint' => 'url',
            'mid' => 'xxx',
            'security_key' => 'xxx',
            'security_seq' => 'xxx',
        ]
    ];

 - *endpoint*: The URL to receive transaction requests.
 - *mid*: Merchant ID generated by WireCard.
 - *security_key*: Security parameters.
 - *security_seq*: Order to make hashed string, for example: `amt,ref,cur,mid,transtype`

### <a name="make-transaction"></a>Make a Transaction

Set URL to receive payment status and return after customer make payment.

    EasyPay2::set('statusurl', URL);
    EasyPay2::set('returnurl', URL);

Set other payment parameters

 - *Transaction type*, use EPTransactionType class contains types

        EasyPay2::set('transtype', EPTransactionType::SALE);

 - *Skip status page*, that mean EasyPay will don't return status to Merchant website. Don't recommended.

        EasyPay2::set('skipstatuspage', 'N');

 - *Reference ID* is unique value on a transaction. You can use helper in this package to create RefId.

        EasyPay2::set('ref', EPHelper::uniqueStringRandom());

 - *Total amount* to pay

        EasyPay2::set('amt', #.##);

 - Finally, call the method to make request URL with number of munites for validity period

        EasyPay2::makeTransaction(15);
        $requestUrl = EasyPay2::requestUrl();
   
### <a name="send-transaction"></a>Send Transaction
Use a Laravel helper to redirect customer to Payment page

    return redirect($requestUrl);

### <a name="receive-status-response"></a>Receive Payment status
This process totally in backend, customer can't see Status response. After customer do payment, EasyPay will send a **POST** request to *statusurl* with parameters ([see workflow image](#workflow-image)).

In your controller, get all inputs

    public function postEasyPayStatusResponse(Request $request)
    {
         $response = $request->all();
         
        //...

 - Check `TM_Signature` value in response is valid, formula is `hashed string from security sequence + status + error + security key`

*For example, now security seq is `amt,ref,cur,mid,transtype` so full formula is*

    $mdHashed : hash512($amt, $ref, $cur, $mid, $transtype) . $status . $error . $security_key
    $epSignature : get('TM_Signature')

Implement, cause Easypay make a new request so we need regenerate values for EasyPay instance. Upon security seq you will set parameters needed for the instance. In the case:

    $status = $this->request->get('TM_Status');
    $error = $this->request->get('TM_Error');
    $security_key = EasyPay2::get('security_key');
    
    EasyPay2::set('amt', $amtOfOldRefId);
    EasyPay2::set('ref', $oldRefId);
    EasyPay2::set('cur', $currencyOfOldRefId);
    EasyPay2::set('transtype', $transtypeOfOldRefId);

    $data = EasyPay2::getHashDataFromSecuritySeq() . $status . $error . $security_key;
    
    $mdHashed = EPHelper::hashSha512($data);
    $signature = $this->request->get('TM_Signature');
 
Check if `$mdHashed` is same `$signature`, the request is valid and make other steps.

### <a name="send-acknowledge-response"></a>Send Acknowledge Response

After check signature in status response, Merchant must to send ACK to EasyPay to confirm receiving request.

    EasyPay2::set('ack', 'YES');
    $requestUrl = EasyPay2::requestUrl();

### <a name="send-void-request"></a>Send REVERSAL / VOID request if don't receive status response

> Content updating ...


## License
This EasyPay2 for Laravel 5 is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)