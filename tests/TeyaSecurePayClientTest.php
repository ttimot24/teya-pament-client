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
            "Currency" => "HUF",
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

        $this->client->addItems([
            new \Ttimot24\TeyaPayment\Model\TeyaItem('Test Item', 1, 10000)
        ]);

        $redirect_url = $this->client->start([
            "orderid" => "TEST00000001",
        ]);

        $this->assertMatchesRegularExpression('/'.urlencode('checkhash=').'/', $redirect_url);
        $this->assertMatchesRegularExpression('/'.urlencode('orderid=TEST00000001').'/', $redirect_url);
        $this->assertMatchesRegularExpression('/'.urlencode('amount=10000').'/', $redirect_url);
    }

}