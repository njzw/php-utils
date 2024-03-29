<?php

namespace Nigel\Utils\Core\Phone;

class Phone
{
    protected string $phone;
    protected string $countryCode;

    public function __construct(string $phone, string $countryCode = 'ZW')
    {
        $this->phone = $phone;
        $this->countryCode = $countryCode;
    }

    /**
     * Get phone number in international format
     *
     * @return string
     */
    public function internationalFormat()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getE164Format()->done();

            return str_replace("+", "", $phone['standardFormat']);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * Get phone number in national format
     *
     * @return string
     */
    public function nationalFormat()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getNationalFormat()->done();

            return str_replace("+", "", $phone['nationalFormat']);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }


    /**
     * check  if phone number is valid
     *
     * @return string
     */
    public function isValid()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getIsValid()->done();

            return $phone['isValid'];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * get country name from phone
     *
     * @return string
     */
    public function getCountry()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getPhoneDescription()->done();

            return $phone['country'];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * get country name from phone
     *
     * @return string
     */
    public function providerInfo()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getCarrierProvider()->done();

            return $phone['carrierProvider'];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    /**
     * get timezone from phone
     *
     * @return string
     */
    public function timeZoneInfo()
    {
        try {
            $phone = (new PhoneLibrary($this->phone, $this->countryCode))->parse()->getTimeZone()->done();

            return $phone['timeZone'];
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}