export { plural };

function plural(single: string, plural: string, count: number): string
{
    var word = '';
    count == 1 ? word = single : word = plural;
    return word;
}
