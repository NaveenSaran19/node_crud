const path = require('path');
module.paths.push(path.join(__dirname, 'node_modules'));
module.paths.push(path.join(__dirname, 'backend', 'node_modules'));
process.env.NODE_PATH = (process.env.NODE_PATH ? process.env.NODE_PATH + ';' : '') + 
    path.join(__dirname, 'node_modules') + ';' + 
    path.join(__dirname, 'backend', 'node_modules');

require('./backend/server.js');
