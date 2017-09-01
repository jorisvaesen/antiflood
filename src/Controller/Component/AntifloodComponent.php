<?php
namespace JorisVaesen\Antiflood\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Utility\Security;

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
        'salt' => true,
    ];

    public function increment($identifier = null)
    {
        $identifier = $this->_identifier($identifier);

        if (!Cache::read($identifier, $this->getConfig('cacheKey'))) {
            Cache::write($identifier, 0, $this->getConfig('cacheKey'));
        }

        Cache::increment($identifier, 1, $this->getConfig('cacheKey'));
    }

    public function check($identifier = null)
    {
        $identifier = $this->_identifier($identifier);
        $counter = Cache::read($identifier, $this->getConfig('cacheKey'));

        if (!$counter) {
            return true;
        }

        return $counter < $this->getConfig('maxAttempts');
    }

    private function _identifier($identifier = null)
    {
        $identifier = md5($identifier);

        if ($this->getConfig('ip')) {
            $identifier .= '_' . md5($this->_registry->getController()->request->clientIp());
        }

        if ($this->getConfig('salt') === true) {
            $identifier .= '_' . md5(Security::salt());
        } else if (is_string($salt)) {
            $identifier .= '_' . md5($this->getConfig('salt'));
        }

        return $identifier;
    }
}
