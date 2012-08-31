var connect = require('connect'),
    http    = require('http'),
    fs      = require('fs'),
    app     = connect().use(connect.static(__dirname + '/../public/'))
            ;

http.createServer(app).listen(3000);

fs.writeFileSync(__dirname + '/server.pid', process.pid, 'utf-8');