<?php

namespace Nigel\Utils\Core\Phone;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberToCarrierMapper;
use libphonenumber\PhoneNumberToTimeZonesMapper;
use libphonenumber\geocoding\PhoneNumberOfflineGeocoder;


class PhoneLibrary
{
    /**
     * Phone number to parse
     *
     * @var string
     */
    protected string $phoneNumber;

    /**
     * Country code 
     *
     * @var string
     */
    protected string $countryCode;

    /**
     * response data merged
     *
     * @var array
     */
    protected array $response = [];

    /**
     * Phone util instance
     *
     */
    protected $phoneUtil;

    /**
     * phone number parsed
     *
     */
    protected $phoneNumberProto;

    public function __construct(string $phoneNumber, string $countryCode)
    {
        $this->phoneNumber = $phoneNumber;
        $this->countryCode = $countryCode;

        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    /**
     * Parse phone number
     *
     * @return self
     */
    public function parse()
    {
        $phoneUtil = $this->phoneUtil;

        try {
            $phoneNumberProto = $phoneUtil->parse($this->phoneNumber, $this->countryCode);
            $this->setPhoneNumberProto($phoneNumberProto);

            return $this;
        } catch (\Throwable $th) {
            $this->setResponse(["parseError" => $th->getMessage()]);

            return $this;
        }
    }

    /**
     * Get if phone is valid
     *
     * @return self
     */
    public function getIsValid()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = $this->phoneUtil;

        $isValid = $phoneUtil->isValidNumber($phoneNumberProto);

        if ($isValid) {
            $this->setResponse(['isValid' => 'true']);
            return $this;
        }

        $this->setResponse(['isValid' => 'false']);
        return $this;
    }

    /**
     * Get national format
     *
     * @return self
     */
    public function getNationalFormat()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = $this->phoneUtil;

        $this->setResponse(['nationalFormat' => str_replace(" ", "", $phoneUtil->format($phoneNumberProto, PhoneNumberFormat::NATIONAL))]);

        return $this;
    }

    /**
     * Get international format
     *
     * @return self
     */
    public function getInternationalFormat()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = $this->phoneUtil;

        $this->setResponse(['internationalFormat' => $phoneUtil->format($phoneNumberProto, PhoneNumberFormat::INTERNATIONAL)]);

        return $this;
    }

    /**
     * Get E164 format
     *
     * @return self
     */
    public function getE164Format()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = $this->phoneUtil;

        $this->setResponse(['standardFormat' => $phoneUtil->format($phoneNumberProto, PhoneNumberFormat::E164)]);

        return $this;
    }

    /**
     * Get carrier provider
     *
     * @return self
     */
    public function getCarrierProvider()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = PhoneNumberToCarrierMapper::getInstance();

        $this->setResponse(['carrierProvider' => $phoneUtil->getNameForNumber($phoneNumberProto, "en")]);

        return $this;
    }

    /**
     * Get country details
     *
     * @return self
     */
    public function getPhoneDescription()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = PhoneNumberOfflineGeocoder::getInstance();

        $this->setResponse(['country' => $phoneUtil->getDescriptionForNumber($phoneNumberProto, "en_US")]);

        return $this;
    }

    /**
     * Get time zone
     *
     * @return self
     */
    public function getTimeZone()
    {
        $phoneNumberProto = $this->phoneNumberProto;
        $phoneUtil = PhoneNumberToTimeZonesMapper::getInstance();

        $zone = $phoneUtil->getTimeZonesForNumber($phoneNumberProto);
        $this->setResponse(['timeZone' => $zone[0]]);

        return $this;
    }

    /**
     * Get merged response data
     *
     * @return array
     */
    public function done()
    {
        $response = $this->response;
        $response = array_merge([], ...$response);

        return $response;
    }

    /**
     * Get phone number to parse
     *
     * @return  string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set phone number to parse
     *
     * @param  string  $phoneNumber  Phone number to parse
     *
     * @return  self
     */
    public function setPhoneNumber(string $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get country code
     *
     * @return  string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set country code
     *
     * @param  string  $countryCode  Country code
     *
     * @return  self
     */
    public function setCountryCode(string $countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get response data merged
     *
     * @return  array
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response data merged
     *
     * @param  array  $response  response data merged
     *
     * @return  self
     */
    public function setResponse(array $response)
    {
        array_push($this->response, $response);

        return $this;
    }

    /**
     * Get phone util instance
     */
    public function getPhoneUtil()
    {
        return $this->phoneUtil;
    }

    /**
     * Set phone util instance
     *
     * @return  self
     */
    public function setPhoneUtil($phoneUtil)
    {
        $this->phoneUtil = $phoneUtil;

        return $this;
    }

    /**
     * Get phone number parsed
     */
    public function getPhoneNumberProto()
    {
        return $this->phoneNumberProto;
    }

    /**
     * Set phone number parsed
     *
     * @return  self
     */
    public function setPhoneNumberProto($phoneNumberProto)
    {
        $this->phoneNumberProto = $phoneNumberProto;

        return $this;
    }
}
