export class Grume {
    static name = 'grume'
    static comp = 'Grume'
    static store = null;
    static columns = [
        'number',
        'length',
        'diameter',
        'volume',
        'container_number'
    ]
    static creatableColumns = [
        'number',
        'length',
        'diameter'
    ]
    static editableColumns = [
        'length',
        'diameter',
    ]
    static fileExtension = 'xlsx'
    static filePrefix = 'grumes'

}
