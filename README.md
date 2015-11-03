# memcached-sessions
Session handler for with Zend Framework 1.
That handler allows you to use a memcached server as storage for your sessions.

# how to install
composer require alez/memcached-sessions

# how to use
Add to your application.ini
``` resources.session.saveHandler.class = "MemcachedSessions_Handler" ```
If you have your memcached server somewhere else but local machine add some options at your taste.
```
resources.session.saveHandler.options.lifetime = 1800;
resources.session.saveHandler.options.automatic_serialization = true;
resources.session.saveHandler.options.host = "127.0.0.1";
resources.session.saveHandler.options.port = 11211;
resources.session.saveHandler.options.persistent = true;
resources.session.saveHandler.options.compression = true;
```
These are by default.