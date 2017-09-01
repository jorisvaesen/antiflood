<?php
namespace Antiflood\Controller\Component;

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

    public function increment($identifier = null, $options = [])
    {
        $options += [
            'ip' => true,
            'cacheKey' => 'counter'
        ];

        $identifier = $this->_identifier($identifier, $options['ip']);

        if (!Cache::read($identifier, $options['cacheKey'])) {
            Cache::write($identifier, 0, $options['cacheKey']);
        }

        Cache::increment($identifier, 1, $options['cacheKey']);
    }

    public function check($identifier = null, $options = [])
    {
        $options += [
            'ip' => true,
            'cacheKey' => 'counter',
            'maxAttempts' => 3
        ];

        $identifier = $this->_identifier($identifier, $options['ip']);
        $counter = Cache::read($identifier, $options['cacheKey']);

        if (!$counter) {
            return true;
        }

        return $counter < $options['maxAttempts'];
    }

    private function _identifier($identifier = null, $ip = true)
    {
        if ($ip) {
            $identifier .= '__' . $this->_registry->getController()->request->clientIp();
        }

        return md5($identifier);
    }
}
