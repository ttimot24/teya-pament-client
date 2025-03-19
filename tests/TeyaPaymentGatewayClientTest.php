<?php 

use PHPUnit\Framework\TestCase;

class TeyaPaymentGatewayClientTest extends TestCase {

    private $client;

    private $payment = [
        'Amount' => 100,
        'Currency' => 352,
        'OrderId' => 'ORDER1230001',
        'PaymentMethod' => [
            'PaymentType' => 'Card',
            'PAN' => '4176669999000104',
            'ExpYear' => 2031,
            'ExpMonth' => 12
        ]
    ];

    public function setUp(): void {

        $this->client = new Ttimot24\TeyaPayment\TeyaPaymentGatewayClient(['PrivateKey' => '856293_pr0lxnW8PG1SeCwVJ3WPH0lXCeU0/sYLtX', 'log_enabled' => true, 'log_level' => 'debug']);

    }

    public function testPreAuthorization(){

        $message = $this->client->preauth($this->payment);

        $this->assertInstanceOf(\Ttimot24\TeyaPayment\Model\TeyaTransactionMessage::class, $message);
        $this->assertNotNull($message->getTransactionStatus());
    }

    public function testPayment(){

        $message = $this->client->payment($this->payment);

        $this->assertInstanceOf(\Ttimot24\TeyaPayment\Model\TeyaTransactionMessage::class, $message);
        $this->assertNotNull($message->getTransactionStatus());
    }

    public function testTransaction(){

        $message = $this->client->transaction('tr_29yag5DnGmMAsl-F79Xiomz2UxPpaX_g');

        $this->assertInstanceOf(\Ttimot24\TeyaPayment\Model\TeyaTransactionMessage::class, $message);
        $this->assertNotNull($message->getTransactionStatus());
    }

    public function testCapture(){

        $response = $this->client->capture('tr_29yag5DnGmMAsl-F79Xiomz2UxPpaX_g');
    //    $this->assertContains('TransactionStatus', array_keys(json_decode($response->getBody()->__toString(), true)));
    }

    public function testCancel(){

        $response = $this->client->cancel('tr_29yag5DnGmMAsl-F79Xiomz2UxPpaX_g');
      //  $this->assertContains('TransactionStatus', array_keys(json_decode($response->getBody()->__toString(), true)));
    }

    public function testRefund(){

        $response = $this->client->refund('tr_29yag5DnGmMAsl-F79Xiomz2UxPpaX_g');
        // $this->assertContains('TransactionStatus', array_keys(json_decode($response->getBody()->__toString(), true)));
    }

}