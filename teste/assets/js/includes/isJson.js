/**
 * Verifica se uma string é do tipo JSon
 * @param str
 * @returns {boolean}
 */
function isJson(str)
{
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }

    return true;
}