import json as enc
import gettext
def gettext_json(domain="system", path="C:\wamp\www\joomla\components\com_tuiyo\locale\en_GB\LC_MESSAGES", lang = [], indent = False):
    try:
        tr = gettext.translation(domain, path, lang)
        # for unknown reasons, instead of having plural entries like 
        # key: [sg, pl1...]
        # tr._catalog has (key, n): pln, 
        keys = tr._catalog.keys()
        keys.sort()
        ret = {}
        for k in keys:
            v = tr._catalog[k]
            if type(k) is tuple:
                if k[0] not in ret:
                    ret[k[0]] = []
                ret[k[0]].append(v)
            else:
                ret[k] = v
        return enc.dumps(ret, ensure_ascii = False, indent = indent)
    except IOError:
        return None
