export class Container {
    static name = 'container'
    static comp = 'Container'
    static store = null;
    static columns = [
        'number',
        'tare',
        'seal',

    ]
    static creatableColumns = [
        'number',
        'tare',
        'seal'
    ]
    static editableColumns = [
        'tare',
        'seal',
    ]
    static fileExtension = 'xlsx'
    static filePrefix = 'containers'

}
