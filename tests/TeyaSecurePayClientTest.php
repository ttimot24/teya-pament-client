<?php 

use PHPUnit\Framework\TestCase;

class TeyaSecurePayClientTest extends TestCase {

    private $client;

    protected function setUp(): void {

        $this->client = new Ttimot24\TeyaPayment\TeyaSecurePayClient([
            'MerchantId' => '9256684', 
            'PaymentGatewayId' => 7, 
            'SecretKey' => 'cdedfbb6ecab4a4994ac880144dd92dc',
            'RedirectSuccess' => '/SecurePay/SuccessPage.aspx?PaymentID=',
            'RedirectSuccessServer' => 'SUCCESS_SERVER',
            'log_enabled' => true, 'log_level' => 'debug'
        ]);

    }

    public function testSignatureCalulation(){

        $signatureClient = new Ttimot24\TeyaPayment\TeyaSecurePayClient([
            'MerchantId' => '9123456', 
            'PaymentGatewayId' => 16, 
            'SecretKey' => '1234567890abcdef',
            'RedirectSuccess' => 'https://borgun.is/success',
            'RedirectSuccessServer' => 'https://borgun.is/success_server'
        ]);

        $checkHash = $signatureClient->getSignature([
            "amount" => 100,
            "currency" => "ISK",
            "orderid" => "TEST00000001"
        ]);

        $this->assertEquals("ef2e66e64df91143e7e98ecc9f94e12988718408b860770b4181e466401f22d0", $checkHash);
    }

    public function testStartTransaction(){

        $redirect_url = $this->client->start([
            "amount" => 10000,
            "currency" => "HUF",
            "orderid" => "TEST00000001",
            "buyername" => "Valaki",
            "buyeremail" => "test@borgun.is",
            "returnurlcancel" => "http://borgun.is/ReturnPageCancel.aspx",
            "returnurlerror" => "http://borgun.is/ReturnUrlError.aspx",
            "itemdescription_0" => "Test Item",
            "itemcount_0" => 1,
            "itemunitamount_0" => 10000,
            "itemamount_0" => 10000,
            "pagetype" => 0,
            "skipreceiptpage" => 0,
            "merchantemail" => "test@borgun.is"
        ]);

        $this->assertMatchesRegularExpression('/'.urlencode('&checkhash=').'/', $redirect_url);
        $this->assertMatchesRegularExpression('/'.urlencode('&orderid=').'/', $redirect_url);
    }

}