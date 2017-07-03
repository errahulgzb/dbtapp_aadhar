<?php

use Psr\Log\LoggerInterface;

/**
 * Assembles and dispatches the SOAP request body XML and returns the
 * Response body XML from the vendor API.
 */
class Request
{
    /**
     * @var \SoapClient
     */
    protected $client;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    public function __construct(\SoapClient $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    public function call()
    {
        if (function_exists('xdebug_disable')) {
            xdebug_disable();
        }

        $xml = '
<some_ns1:List
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:some_ns1="foo:enterprise.some.company.com/some_ns1">
    <some_ns1:SomeObject xsi:type="some_ns1:ListRequests">
        <objs:ItemId>Value1</objs:ItemId>
        <objs:MoreStuff>Value2</objs:MoreStuff>
        <objs:AnotherThing>Value3</objs:AnotherThing>
    </some_ns1:SomeObject>
</some_ns1:List>';
        $soapBody = new \SoapVar($xml, \XSD_ANYXML);

        $return = $this->client->__SoapCall('Create', array($soapBody));
        $this->logger->debug('Sent SOAP Request XML: ' . $this->getLastRequestXml());

        return $return;
    }

    public function getSoapClient()
    {
        return $this->client;
    }

    /**
     * Get most recent XML Request sent to SOAP server
     *
     * @return string
     */
    public function getLastRequestXml()
    {
        return $this->client->__getLastRequest();
    }

    /**
     * Get most recent XML Response returned from SOAP server
     *
     * @return string
     */
    public function getLastResponseXml()
    {
        return $this->client->__getLastResponse();
    }
}
