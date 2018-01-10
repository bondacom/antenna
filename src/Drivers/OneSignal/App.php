<?php

namespace Bondacom\Antenna\Drivers\OneSignal;

use Bondacom\Antenna\Drivers\AppInterface;
use Bondacom\Antenna\Exceptions\MissingOneSignalData;

class App implements AppInterface
{
    /**
     * @var AntennaRequester
     */
    private $requester;

    /**
     * OneSignalConsumer constructor.
     * @param Requester $requester
     */
    public function __construct(Requester $requester)
    {
        $this->requester = $requester;
    }

    /**
     * @param array $data
     * @return array
     * @throws AntennaServerException
     */
    public function create(array $data) : array
    {
        $this->assertDataCreation($data);
        $result = $this->requester->post('apps', $data);
        $this->assertHasNotErrors($result);

        return $result;
    }

    /**
     * @param string $id
     * @return array
     * @throws AntennaServerException
     */
    public function find(string $id) : array
    {
        $result = $this->requester->get('apps/'.$id);
        $this->assertHasNotErrors($result);

        return $result;
    }

    /**
     * @param array $data
     * @param string $id
     * @return array
     * @throws AntennaServerException
     */
    public function update(array $data, string $id) : array
    {
        $result = $this->requester->put('apps/'.$id, $data);
        $this->assertHasNotErrors($result);

        return $result;
    }

    /**
     * Validate if we have the minimum required data to create an app
     *
     * @param $data
     * @throws MissingOneSignalData
     * @return $this
     */
    private function assertDataCreation($data)
    {
        // For references about the fields go to https://documentation.onesignal.com/reference#create-an-app
        $fields = [
            //App name, REQUIRED
            'name' => true,

            // IOS Configuration
            'apns_env' => false,
            'apns_p12' => false,
            'apns_p12_password' => false,

            // Android Configuration
            'gcm_key' => false,
            'android_gcm_sender_id' => false,

            // Web notifications (Chrome and Firefox)
            'chrome_web_origin' => false,
            'chrome_web_default_notification_icon' => false,
            'chrome_web_sub_domain' => false,

            // Web notifications (Safari)
            'safari_apns_p12' => false,
            'safari_apns_p12_password' => false,
            'site_name' => false,
            'safari_site_origin' => false,
            'safari_icon_256_256' => false,

            //Chrome extension
            'chrome_key' => false
        ];

        foreach ($fields as $key => $value) {
            if ($value && !array_key_exists($key, $data)) {
                throw new MissingOneSignalData($key);
            }
        }

        return $this;
    }

    /**
     * @param $result
     * @return $this
     * @throws AntennaServerException
     */
    private function assertHasNotErrors($result)
    {
        if (array_key_exists('errors', $result)) {
            throw new AntennaServerException(implode(', ', $result['errors']));
        }

        return $this;
    }
}