<?php
namespace JorisVaesen\Antiflood\Controller\Component;

use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
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
        'cacheConfig' => 'antiflood',
        'maxAttempts' => 3,
        'salt' => true,
        'log' => false,
    ];

    public function increment($identifier = null)
    {
        $hashedIdentifier = $this->_identifier($identifier);

        if (!Cache::read($hashedIdentifier, $this->getConfig('cacheConfig'))) {
            Cache::write($hashedIdentifier, 0, $this->getConfig('cacheConfig'));
        }

        Cache::increment($hashedIdentifier, 1, $this->getConfig('cacheConfig'));
    }

    public function check($identifier = null)
    {
        $hashedIdentifier = $this->_identifier($identifier);
        $counter = Cache::read($hashedIdentifier, $this->getConfig('cacheConfig'));

        if (!$counter) {
            return true;
        }

        $result = $counter < $this->getConfig('maxAttempts');

        if (!$result) {
            $diff = $this->getConfig('maxAttempts') - $counter;
            if ($diff === 0 && $this->getConfig('log') !== false) {
                Cache::increment($hashedIdentifier, 1, $this->getConfig('cacheConfig'));
                $this->_log($identifier);
            }
        }

        return $result;
    }

    private function _identifier($identifier = null)
    {
        $identifier = md5($identifier);

        if ($this->getConfig('ip')) {
            $identifier .= md5($this->_registry->getController()->request->clientIp());
        }

        $salt = $this->getConfig('salt');
        if ($salt === true) {
            $identifier .= md5(Security::salt());
        } else if (is_string($salt)) {
            $identifier .= md5($salt);
        }

        return $identifier;
    }

    private function _log($identifier = null)
    {
        if (is_callable($this->getConfig('log'))) {
            return call_user_func($this->config('log'), $identifier, $this->_registry->getController()->request);
        }

        $table = TableRegistry::get('JorisVaesen/Antiflood.Antifloods');
        $entity = $table->newEntity([
            'ip' => $this->_registry->getController()->request->clientIp(),
            'identifier' => $identifier,
        ]);
        $table->save($entity);
    }
}
