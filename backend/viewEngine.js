const fs = require('fs');
const path = require('path');

const viewsDir = path.join(__dirname, '../views_php');

function renderView(viewName, data = {}) {
    const filePath = path.join(viewsDir, viewName.replace(/\.php$/, '') + '.php');
    if (!fs.existsSync(filePath)) {
        throw new Error('View not found: ' + filePath);
    }
    const rawContent = fs.readFileSync(filePath, 'utf8');

    // Check if it extends a layout
    const extendMatch = rawContent.match(/<\?=\s*\$this->extend\(['"](.*?)['"]\)\s*\?>/);
    if (extendMatch) {
        const layoutName = extendMatch[1];
        const sectionMatch = rawContent.match(/<\?=\s*\$this->section\(['"]content['"]\)\s*\?>([\s\S]*?)<\?=\s*\$this->endSection\(\)\s*\?>/);
        const innerContent = sectionMatch ? sectionMatch[1] : rawContent.replace(extendMatch[0], '');

        const innerHtml = compileAndExecute(innerContent, data, viewName);

        const layoutData = Object.assign({}, data, {
            __sectionContent: innerHtml
        });
        return renderView(layoutName, layoutData);
    }

    return compileAndExecute(rawContent, data, viewName);
}

function compileAndExecute(phpCode, data, viewName = 'unknown') {
    const compiledJs = compilePhpToJs(phpCode);

    const helpers = {
        base_url: (url = '') => url ? (url.startsWith('http') ? url : '/' + String(url).replace(/^\/+/, '')) : '/',
        current_url: () => typeof data.current_url === 'function' ? data.current_url() : (data.current_url || '/'),
        esc: (val) => val == null ? '' : String(val).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'),
        empty: (val) => val == null || val === '' || val === false || (Array.isArray(val) && val.length === 0) || (typeof val === 'object' && Object.keys(val).length === 0),
        count: (val) => !val ? 0 : (Array.isArray(val) ? val.length : (typeof val === 'object' ? Object.keys(val).length : 0)),
        in_array: (needle, haystack) => Array.isArray(haystack) ? haystack.includes(needle) : false,
        strpos: (h, n) => String(h || '').indexOf(n),
        time: () => Math.floor(Date.now() / 1000),
        trim: (s) => String(s || '').trim(),
        round: (n, d = 0) => Math.round(Number(n) * Math.pow(10, d)) / Math.pow(10, d),
        max: (...args) => Math.max(...args),
        min: (...args) => Math.min(...args),
        date: (fmt, ts) => new Date((ts ? ts * 1000 : Date.now())).toLocaleDateString('en-IN'),
        json_encode: (v) => JSON.stringify(v),
        json_decode: (s) => { try { return JSON.parse(s); } catch { return []; } },
        number_format: (n, d = 2) => Number(n || 0).toLocaleString('en-IN', { minimumFractionDigits: d, maximumFractionDigits: d }),
        strip_tags: (s) => String(s || '').replace(/<[^>]*>/g, ''),
        file_exists: (fp) => typeof fp === 'string' && fs.existsSync(fp),
        FCPATH: path.join(__dirname, '../public/'),
        csrf_field: () => '',
        csrf_hash: () => '',
        csrf_token: () => 'csrf_token',
        old: (k, def = '') => def,
        session: () => ({
            getFlashdata: (k) => (data.raw_session && data.raw_session[k]) || null,
            get: (k) => (data.raw_session && data.raw_session[k]) || (data.session && typeof data.session === 'object' && data.session[k]) || (k === 'admin_name' ? 'Administrator' : null)
        }),
        view: (name, subData = {}) => renderView(name, Object.assign({}, data, subData)),
        strlen: (str) => String(str || '').length,
        intval: (v) => parseInt(v || 0, 10),
        floatval: (v) => parseFloat(v || 0),
        is_numeric: (v) => !isNaN(v) && !isNaN(parseFloat(v)),
        str_repeat: (str, count) => String(str || '').repeat(count || 0),
        str_pad: (str, len, pad = ' ', type = 'right') => {
            const s = String(str || '');
            return type === 'left' ? s.padStart(len, pad) : s.padEnd(len, pad);
        },
        STR_PAD_LEFT: 'left',
        STR_PAD_RIGHT: 'right',
        STR_PAD_BOTH: 'both',
        nl2br: (str) => String(str || '').replace(/\r?\n/g, '<br />'),
        htmlspecialchars: (str) => String(str || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;'),
        htmlspecialchars_decode: (str) => String(str || '').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"'),
        addslashes: (str) => String(str || '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0'),
        stripslashes: (str) => String(str || '').replace(/\\'/g, "'").replace(/\\"/g, '"').replace(/\\\\/g, '\\'),
        ceil: (n) => Math.ceil(Number(n || 0)),
        floor: (n) => Math.floor(Number(n || 0)),
        abs: (n) => Math.abs(Number(n || 0)),
        rand: (min = 0, max = 100) => Math.floor(Math.random() * (max - min + 1)) + min,
        mt_rand: (min = 0, max = 100) => Math.floor(Math.random() * (max - min + 1)) + min,
        reset: (arr) => Array.isArray(arr) ? arr[0] : (arr && typeof arr === 'object' ? Object.values(arr)[0] : null),
        end: (arr) => Array.isArray(arr) ? arr[arr.length - 1] : (arr && typeof arr === 'object' ? Object.values(arr).slice(-1)[0] : null),
        current: (arr) => Array.isArray(arr) ? arr[0] : (arr && typeof arr === 'object' ? Object.values(arr)[0] : null),
        key: (arr) => arr && typeof arr === 'object' ? Object.keys(arr)[0] : 0,
        compact: (...names) => {
            const res = {};
            for (const n of names) {
                if (data[n] !== undefined) res[n] = data[n];
            }
            return res;
        },
        array_map: (fn, arr) => {
            if (typeof fn === 'string') {
                const fnName = fn;
                fn = (x) => fnName === 'trim' ? String(x || '').trim() : x;
            }
            return (arr || []).map(fn);
        },
        array_column: (arr, col) => (arr || []).map(item => item && item[col]),
        array_merge: (...arrays) => [].concat(...arrays.filter(Boolean)),
        array_slice: (arr, start, len) => len !== undefined ? (arr || []).slice(start, start + len) : (arr || []).slice(start),
        array_values: (obj) => Object.values(obj || {}),
        array_keys: (obj) => Object.keys(obj || {}),
        is_array: (a) => Array.isArray(a),
        filter_var: (v, f) => v,
        ucwords: (s) => String(s || '').replace(/\b\w/g, l => l.toUpperCase()),
        ucfirst: (s) => String(s || '').charAt(0).toUpperCase() + String(s || '').slice(1),
        implode: (glue, pieces) => {
            if (Array.isArray(pieces)) return pieces.join(glue);
            if (Array.isArray(glue)) return glue.join(pieces || '');
            return '';
        },
        explode: (delimiter, string) => String(string || '').split(delimiter),
        urlencode: (s) => encodeURIComponent(s || ''),
        rawurlencode: (s) => encodeURIComponent(s || ''),
        urldecode: (s) => decodeURIComponent(s || ''),
        substr: (s, start, len) => len !== undefined ? String(s || '').substr(start, len) : String(s || '').substr(start),
        strtoupper: (s) => String(s || '').toUpperCase(),
        strtolower: (s) => String(s || '').toLowerCase(),
        str_starts_with: (str, prefix) => String(str || '').startsWith(prefix),
        str_ends_with: (str, suffix) => String(str || '').endsWith(suffix),
        str_contains: (str, needle) => String(str || '').includes(needle),
        function_exists: (fn) => typeof helpers[fn] === 'function' || typeof data[fn] === 'function' || typeof globalThis[fn] === 'function',
        preg_match: (pattern, subject) => {
            try {
                if (typeof pattern === 'string' && pattern.startsWith('/')) {
                    const lastSlash = pattern.lastIndexOf('/');
                    const p = pattern.slice(1, lastSlash);
                    const flags = pattern.slice(lastSlash + 1);
                    return new RegExp(p, flags).test(subject || '') ? 1 : 0;
                }
                return new RegExp(pattern).test(subject || '') ? 1 : 0;
            } catch {
                return 0;
            }
        },
        preg_replace: (pattern, replacement, subject) => {
            try {
                if (typeof pattern === 'string' && pattern.startsWith('/')) {
                    const lastSlash = pattern.lastIndexOf('/');
                    const p = pattern.slice(1, lastSlash);
                    const flags = pattern.slice(lastSlash + 1);
                    return String(subject || '').replace(new RegExp(p, flags), replacement);
                }
                return String(subject || '').replace(pattern, replacement);
            } catch {
                return String(subject || '');
            }
        },
        service: (s) => ({
            getTotalSegments: () => 0,
            getSegment: () => ''
        }),
        ltrim: (str, chars = '/') => {
            if (!str) return '';
            const set = new Set(String(chars));
            let i = 0;
            while (i < str.length && set.has(str[i])) i++;
            return str.slice(i);
        },
        rtrim: (str, chars = '/') => {
            if (!str) return '';
            const set = new Set(String(chars));
            let i = str.length;
            while (i > 0 && set.has(str[i - 1])) i--;
            return str.slice(0, i);
        },
        preg_split: (pattern, str) => String(str || '').split(/\s+/),
        usort: (arr, fn) => { if (Array.isArray(arr)) arr.sort(fn); return true; },
        array_filter: (arr, fn) => {
            if (Array.isArray(arr)) return fn ? arr.filter(fn) : arr.filter(Boolean);
            if (arr && typeof arr === 'object') {
                const res = {};
                Object.entries(arr).forEach(([k, v]) => { if (fn ? fn(v, k) : Boolean(v)) res[k] = v; });
                return res;
            }
            return [];
        },
        str_replace: (search, replace, subject) => {
            if (Array.isArray(search)) {
                let res = String(subject || '');
                search.forEach((s, idx) => {
                    const rep = Array.isArray(replace) ? replace[idx] : replace;
                    res = res.replaceAll(s, rep);
                });
                return res;
            }
            return String(subject || '').replaceAll(search, replace);
        },
        isset: (v) => v !== undefined && v !== null,
        array_unique: (a) => Array.from(new Set(a || [])),
        newModel: (modelName) => {
            const settings = data.settings || {};
            const chain = {
                where: (k, v) => chain,
                orderBy: (col, dir) => chain,
                like: () => chain,
                limit: () => chain,
                select: () => chain,
                first: () => null,
                findAll: () => []
            };
            return chain;
        },
        $this: {
            renderSection: (secName) => data.__sectionContent || ''
        }
    };

    const merged = Object.assign({}, helpers, data);

    const scope = new Proxy(merged, {
        has(t, p) {
            if (p in globalThis) return false;
            return true;
        },
        get(t, p) {
            if (p === Symbol.unscopables) return undefined;
            if (t[p] !== undefined) return t[p];
            if (typeof p === 'string' && p.startsWith('$')) {
                return t[p.slice(1)];
            }
            return undefined;
        },
        set(t, p, v) {
            t[p] = v;
            if (typeof p === 'string' && p.startsWith('$')) {
                t[p.slice(1)] = v;
            }
            return true;
        }
    });

    try {
        const fn = new Function('scope', 'with(scope) { let out = "";\n' + compiledJs + '\nreturn out; }');
        return fn(scope);
    } catch (err) {
        fs.writeFileSync(path.join(__dirname, `debug_${viewName.replace(/\//g, '_')}.js`), 'function test(scope) {\nwith(scope) {\nlet out = "";\n' + compiledJs + '\nreturn out;\n}\n}');
        console.error(`Compilation Error in view [${viewName}]:`, err.message);
        const lines = compiledJs.split('\n');
        for (let i = Math.max(0, 540); i < Math.min(lines.length, 570); i++) {
            console.log((i + 1) + ': ' + lines[i]);
        }
        throw err;
    }
}

function compilePhpToJs(phpCode) {
    let js = '';
    let pos = 0;

    const regex = /<\?(=|php)?([\s\S]*?)\?>/g;
    let match;

    function addHtml(str) {
        if (!str) return;
        js += 'out += ' + JSON.stringify(str) + ';\n';
    }

    while ((match = regex.exec(phpCode)) !== null) {
        const textBefore = phpCode.slice(pos, match.index);
        addHtml(textBefore);
        pos = regex.lastIndex;

        const isEcho = match[1] === '=';
        let code = match[2].trim();

        if (isEcho) {
            let expr = code.replace(/;$/, '');
            expr = transformPhpExpr(expr);
            js += 'out += ((' + expr + ') ?? "");\n';
        } else {
            js += transformPhpStmt(code) + '\n';
        }
    }

    addHtml(phpCode.slice(pos));
    return js;
}

function makeSafeLookups(code) {
    let result = '';
    let i = 0;
    while (i < code.length) {
        const match = code.slice(i).match(/^(\$[a-zA-Z0-9_]+)\[/);
        if (match) {
            const varName = match[1];
            let depth = 1;
            let j = i + match[0].length;
            let inString = false;
            let strChar = '';
            while (j < code.length && depth > 0) {
                const c = code[j];
                if (inString) {
                    if (c === strChar && code[j - 1] !== '\\') inString = false;
                } else {
                    if (c === '"' || c === "'") {
                        inString = true;
                        strChar = c;
                    } else if (c === '[') {
                        depth++;
                    } else if (c === ']') {
                        depth--;
                    }
                }
                j++;
            }
            if (depth === 0) {
                const indexExpr = code.slice(i + match[0].length, j - 1);
                const rest = code.slice(j);
                const isAssign = /^\s*=(?!=)/.test(rest);
                if (isAssign) {
                    result += `if (typeof ${varName} === "undefined" || !${varName}) ${varName} = {}; ${varName}[${makeSafeLookups(indexExpr)}]`;
                } else {
                    result += `${varName}?.[${makeSafeLookups(indexExpr)}]`;
                }
                i = j;
                continue;
            }
        }
        result += code[i];
        i++;
    }
    return result;
}

function transformPhpExpr(expr) {
    let s = convertPhpArrays(expr);
    s = makeSafeLookups(s);
    s = s.replace(/\((?:float|double|int|integer)\)/g, 'Number');
    s = s.replace(/\(string\)/g, 'String');
    s = s.replace(/\((?:bool|boolean)\)/g, 'Boolean');
    s = s.replace(/new\s+\\?App\\Models\\([a-zA-Z0-9_]+)\(\)/g, 'newModel("$1")');
    s = s.replace(/\]\s*\[/g, ']?.[');
    s = s.replace(/->/g, '.');
    s = s.replace(/\s+\.\s+/g, ' + ');
    s = s.replace(/\?:/g, '||');
    s = s.replace(/\belseif\b/g, 'else if');
    return s;
}

function convertPhpArrays(code) {
    let result = '';
    let i = 0;
    while (i < code.length) {
        if (code[i] === '[') {
            let depth = 1;
            let j = i + 1;
            let inString = false;
            let strChar = '';
            let hasArrow = false;

            while (j < code.length && depth > 0) {
                const c = code[j];
                if (inString) {
                    if (c === strChar && code[j - 1] !== '\\') inString = false;
                } else {
                    if (c === '"' || c === "'") {
                        inString = true;
                        strChar = c;
                    } else if (c === '[') {
                        depth++;
                    } else if (c === ']') {
                        depth--;
                    } else if (c === '=' && code[j + 1] === '>' && depth === 1) {
                        hasArrow = true;
                    }
                }
                j++;
            }

            if (depth === 0) {
                const inner = code.slice(i + 1, j - 1);
                const convertedInner = convertPhpArrays(inner);
                if (hasArrow) {
                    result += '{' + convertedInner.replace(/=>/g, ':') + '}';
                } else {
                    result += '[' + convertedInner + ']';
                }
                i = j;
                continue;
            }
        }
        result += code[i];
        i++;
    }
    return result;
}

function transformArrayPush(code) {
    let result = '';
    let i = 0;
    while (i < code.length) {
        const match = code.slice(i).match(/^(\$[a-zA-Z0-9_]+)\[\]\s*=\s*/);
        if (match) {
            const varName = match[1];
            i += match[0].length;
            let depth = 0;
            let inString = false;
            let strChar = '';
            let expr = '';
            while (i < code.length) {
                const c = code[i];
                if (inString) {
                    if (c === strChar && code[i - 1] !== '\\') inString = false;
                    expr += c;
                } else {
                    if (c === '"' || c === "'") {
                        inString = true;
                        strChar = c;
                        expr += c;
                    } else if (c === '(' || c === '[' || c === '{') {
                        depth++;
                        expr += c;
                    } else if (c === ')' || c === ']' || c === '}') {
                        depth--;
                        expr += c;
                    } else if (c === ';' && depth === 0) {
                        i++;
                        break;
                    } else {
                        expr += c;
                    }
                }
                i++;
            }
            result += varName + '.push(' + expr + ');\n';
            continue;
        }
        result += code[i];
        i++;
    }
    return result;
}

function transformEchoStatements(code) {
    let result = '';
    let i = 0;
    while (i < code.length) {
        const match = code.slice(i).match(/\becho\s+/);
        if (match && match.index === 0) {
            i += match[0].length;
            let depth = 0;
            let inString = false;
            let strChar = '';
            let expr = '';
            while (i < code.length) {
                const c = code[i];
                if (inString) {
                    if (c === strChar && code[i - 1] !== '\\') inString = false;
                    expr += c;
                } else {
                    if (c === '"' || c === "'") {
                        inString = true;
                        strChar = c;
                        expr += c;
                    } else if (c === '(' || c === '[' || c === '{') {
                        depth++;
                        expr += c;
                    } else if (c === ')' || c === ']' || c === '}') {
                        depth--;
                        expr += c;
                    } else if (c === ';' && depth === 0) {
                        i++;
                        break;
                    } else {
                        expr += c;
                    }
                }
                i++;
            }
            result += 'out += ((' + expr.trim() + ') ?? "");\n';
            continue;
        }
        result += code[i];
        i++;
    }
    return result;
}

function transformPhpStmt(stmt) {
    let s = transformEchoStatements(stmt);
    s = transformArrayPush(s);
    s = convertPhpArrays(s);
    s = makeSafeLookups(s);
    s = s.replace(/\bfunction\s*\((.*?)\)\s*use\s*\((.*?)\)/g, 'function($1)');
    s = s.replace(/\bfunction\s+([a-zA-Z0-9_]+)\s*\(/g, 'var $1 = function(');
    s = s.replace(/\buse\s+[a-zA-Z0-9_\\]+;/g, '');
    s = s.replace(/\bif\s*\((.*?)\)\s*:/g, 'if ($1) {');
    s = s.replace(/\belseif\s*\((.*?)\)\s*:/g, '} else if ($1) {');
    s = s.replace(/\belse\s*:/g, '} else {');
    s = s.replace(/\bendif\s*;/g, '}');

    s = s.replace(/\bforeach\s*\((.*?)\s+as\s+&?(\$[a-zA-Z0-9_]+)\s*=>\s*&?(\$[a-zA-Z0-9_]+)\)\s*[:{]/g, 
        'for (const [$2, $3] of Object.entries($1 || {})) {');
    s = s.replace(/\bforeach\s*\((.*?)\s+as\s+&?(\$[a-zA-Z0-9_]+)\)\s*[:{]/g, 
        'for (const $2 of ($1 || [])) {');
    s = s.replace(/\bendforeach\s*;/g, '}');
    s = s.replace(/\bunset\s*\((.*?)\)\s*;/g, '');

    s = s.replace(/\bfor\s*\((.*?)\)\s*:/g, 'for ($1) {');
    s = s.replace(/\bendfor\s*;/g, '}');

    s = s.replace(/(\$[a-zA-Z0-9_]+)\[\]\s*=\s*(.*?);\s*$/gm, '$1.push($2);');
    s = s.replace(/\((?:float|double|int|integer)\)/g, 'Number');
    s = s.replace(/\(string\)/g, 'String');
    s = s.replace(/\((?:bool|boolean)\)/g, 'Boolean');
    s = s.replace(/<=>/g, '-');
    s = s.replace(/new\s+\\?App\\Models\\([a-zA-Z0-9_]+)\(\)/g, 'newModel("$1")');
    s = s.replace(/\]\s*\[/g, ']?.[');
    s = s.replace(/->/g, '.');
    s = s.replace(/\s+\.\s+/g, ' + ');
    s = s.replace(/\?:/g, '||');
    s = s.replace(/\belseif\b/g, 'else if');

    return s;
}

module.exports = { renderView };
