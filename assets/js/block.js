wp.blocks.registerBlockType('mi-plugin/formulario', {
    title: 'Formulario de Sugerencias',
    icon: 'feedback',
    category: 'common',
    attributes: {
        bgColor: {
            type: 'string',
            default: 'none',
        },
    },
    edit: function(props) {
        return (
            <div>
                <wp.editor.InspectorControls>
                    <wp.components.PanelBody title="Opciones de color de fondo">
                        <wp.components.SelectControl
                            label="Color de fondo"
                            value={props.attributes.bgColor}
                            options={[
                                { value: 'none', label: 'Ninguno' },
                                { value: 'red', label: 'Rojo' },
                                { value: 'green', label: 'Verde' },
                                { value: 'blue', label: 'Azul' },
                            ]}
                            onChange={(newValue) => props.setAttributes({ bgColor: newValue })}
                        />
                    </wp.components.PanelBody>
                </wp.editor.InspectorControls>
                <div style={{ backgroundColor: props.attributes.bgColor }}>
                    <p>Vista previa del formulario (rellenará con HTML real)</p>
                </div>
            </div>
        );
    },
    save: function(props) {
        return (
            <div style={{ backgroundColor: props.attributes.bgColor }}>
                <p>Formulario de sugerencias (HTML se genera aquí en frontend)</p>
            </div>
        );
    },
});
