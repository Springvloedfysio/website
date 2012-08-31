(function() {
    var last  = 0,
        vend  = ['ms', 'moz', 'webkit', 'o'],
        reqFn = 'requestAnimationFrame',
        canFn = 'cancelAnimationFrame';

    for(var x = 0, l = vend.length; x < l && !window[reqFn]; x += 1) {
        window[reqFn] = window[vend[x] + 'RequestAnimationFrame'];
        window[canFn] = window[vend[x] + 'CancelAnimationFrame'] || window[vend[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window[reqFn])
        window[reqFn] = function(callback, element) {
            var curr = +new Date(),
                futu = Math.max(0, 16 - (curr - last)),
                tout = window.setTimeout(function() { callback(curr + futu); }, futu);

            last = curr + futu;

            return id;
        };

    if (!window[canFn])
        window[canFn] = function(id) { clearTimeout(id); };
}());