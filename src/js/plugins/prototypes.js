(function(){

  if (!Function.prototype.partial)
    Function.prototype.partial = function() {
      var fn = this, args = Array.prototype.slice.call(arguments);

      return function() {
        var arg = 0, i = 0, cargs = [];

        for (; i < args.length && arg < arguments.length; i++)
          if (args[i] === undefined)
            cargs[i] = arguments[arg++];
          else
            cargs[i] = args[i];

        for (; arg < arguments.length; i++, arg++)
          cargs[i] = arguments[arg++];

        return fn.apply(this, cargs);
      };
    };
/*
  if (!Function.prototype.onetime)
    Function.prototype.onetime = function() {
      var called           = false,
          orignal_function = this;

      return  function() {
        if (!called) {
          called = true;

          return orignal_function.apply( this, arguments );
        }
      };
    };
// */

})();

