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
    static filterableColumns = [
        'number',
        'length',
        'diameter',
        'volume',
    ]
    static creatableColumns = [
        'number',
        'length',
        'diameter',
        'container_number'
    ]
    static editableColumns = [
        'length',
        'diameter',
        'container_number'
    ]
    static fileExtension = 'xlsx'
    static filePrefix = 'grumes'

}
