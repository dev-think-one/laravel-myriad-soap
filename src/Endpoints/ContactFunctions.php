<?php

namespace MyriadSoap\Endpoints;

class ContactFunctions extends FunctionsSet
{


    /**
     * @param int $customerNumber
     *
     * @return array|mixed
     * @throws \MyriadSoap\MyriadSoapException
     */
    public function getContactDetails(int $customerNumber)
    {
        return $this->api->call('SOAP_getContactDetails', [ 'Contact_ID' => $customerNumber, ]);
    }

    /**
     * @param int $customerNumber
     *
     * @return array|mixed
     * @throws \MyriadSoap\MyriadSoapException
     */
    public function getContactCommunications(int $customerNumber)
    {
        return $this->api->call('SOAP_getContactCommunications', [ 'Contact_ID' => $customerNumber, ]);
    }

    /**
     * @param int $institutionID
     *
     * @return array|mixed
     * @throws \MyriadSoap\MyriadSoapException
     */
    public function getContactIDForInstitution(int $institutionID)
    {
        return $this->api->call('SOAP_getContactIDForInstitution', [ 'Institution_ID' => $institutionID, ]);
    }

    /**
     * @param int $customerNumber
     *
     * @return array|mixed
     * @throws \MyriadSoap\MyriadSoapException
     */
    public function getContactDemographics(int $customerNumber)
    {
        return $this->api->call('SOAP_getContactDemographics', [ 'Contact_ID' => $customerNumber, ]);
    }

    /**
     * @param int $customerNumber
     *
     * @return array|mixed
     * @throws \MyriadSoap\MyriadSoapException
     */
    public function getContactMarketingRules(int $customerNumber)
    {
        return $this->api->call('SOAP_getContactMarketingRules', [ 'Contact_ID' => $customerNumber, ]);
    }
}
