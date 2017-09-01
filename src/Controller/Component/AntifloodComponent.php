<?php
namespace JorisVaesen\Antiflood\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;

/**
 * Antiflood component
 */
class AntifloodComponent extends Component
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'ip' => true,
        'cacheKey' => 'antiflood',
        'maxAttempts' => 3,
    ];

    public function increment($identifier = null)
    {
        $identifier = $this->_identifier($identifier, $this->getConfig('ip'));

        if (!Cache::read($identifier, $options['cacheKey'])) {
            Cache::write($identifier, 0, $this->getConfig('cacheKey'));
        }

        Cache::increment($identifier, 1, $this->getConfig('cacheKey'));
    }

    public function check($identifier = null)
    {
        $identifier = $this->_identifier($identifier, $this->getConfig('ip'));
        $counter = Cache::read($identifier, $this->getConfig('cacheKey'));

        if (!$counter) {
            return true;
        }

        return $counter < $this->getConfig('maxAttempts');
    }

    private function _identifier($identifier = null, $ip = true)
    {
        if ($ip) {
            $identifier .= '__' . $this->_registry->getController()->request->clientIp();
        }

        return md5($identifier);
    }
}
