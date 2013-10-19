Bernard Command Queue
========================

This is an experimental app using bernard and symfony commands.

Try it out, first run the consumer:

```
/app/console bernard:consume
```
(This is going to fork off consumers for all the defined queues)

Try out a simple 'hello' command:

```
./app/console demo:hello Stan
```

Great, but now we want to fire that command off from a queued message:

```
./app/console bernard:compose demo:hello Stan
```

Or instead of proxying the console command we can send it raw:
```
./app/console bernard:produce CommandMessageHandler "{\"command\": \"demo:hello Stan\" }"
```

Todos:
-------------
- Figure out a better way of creating receiver names, considering using Reflection on method names or possibly helper classes to define them
- Create way of defaulting the queue name to be used when producing messages
- Create stock controller for push queues, ie. IronMQ and Google AppEngine
- Test!
