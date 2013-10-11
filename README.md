Bernard Command Queue
========================

This is an experimental app using bernard and symfony commands.

Try it out, first run the consumer:

```
/app/console bernard:consume
```

Try out a simple 'hello' command:

```
/app/console demo:hello "Stan Lemon"
```

Now execute that same command via a message by passing it to the bernard produdcer:

```
/app/console bernard:produce demo:hello "Stan Lemon" --yell
```

Todos:
-------------
- [ ] Make bernard's driver configurable
- [ ] Add example for producing from a controller
- [ ] Test!
