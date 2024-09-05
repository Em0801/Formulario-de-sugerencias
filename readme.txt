DOCUMENTACION

Guía Completa: Instalación del Plugin en Otras Instalaciones de WordPress

Paso 1: Preparar el Plugin

Empaquetar el Plugin: Asegúrate de que todos los archivos del plugin están organizados correctamente en una carpeta con el nombre de tu plugin (por ejemplo, mi-plugin-formulario).

Archivos Necesarios: Verifica que la carpeta contiene todos los archivos necesarios (mi-plugin-formulario.php, carpetas como includes, assets, y otros scripts o archivos CSS relacionados).

Paso 2: Subir el Plugin a WordPress

Acceder al Panel de Administración: Ingresa al panel de administración de WordPress en el sitio donde deseas instalar el plugin.

Navegar a la Sección de Plugins: Ve a la sección "Plugins" en el menú de la barra lateral y selecciona "Añadir nuevo".

Subir el Plugin: Haz clic en "Subir plugin" y selecciona el archivo .zip del plugin que empaquetaste anteriormente. Luego, haz clic en "Instalar ahora".

Activar el Plugin: Una vez que la instalación esté completa, haz clic en "Activar plugin" para comenzar a utilizarlo.

Paso 3: Configurar el Plugin

Regenerar Enlaces Permanentes: Después de activar el plugin, ve a "Ajustes > Enlaces permanentes" en el panel de administración y haz clic en "Guardar cambios" para regenerar las reglas de reescritura.

Verificación: Verifica que el plugin esté funcionando correctamente visitando la URL personalizada o utilizando los shortcodes y bloques de Gutenberg que proporciona el plugin.

Explicación de la Utilidad de Cada Archivo y la Lógica Empleada en el Plugin

1. Archivo Principal del Plugin: mi-plugin-formulario.php

Función: Este archivo es el núcleo del plugin. Contiene el encabezado que define el plugin en WordPress y carga otros archivos necesarios. Gestiona la inicialización del plugin, el registro de scripts y estilos, y define las reglas de reescritura necesarias.

Lógica:

Inicialización: Se encarga de registrar el Custom Post Type (CPT), agregar reglas de reescritura, y configurar los hooks necesarios para el funcionamiento del plugin.

Cargar Scripts y Estilos: Encola los archivos CSS y JavaScript necesarios para que el plugin funcione correctamente en el front-end y en el editor de bloques de Gutenberg.

2. Carpeta includes

Función: Contiene las clases que manejan diferentes funcionalidades del plugin.

Archivos:

class-form-handler.php: Maneja la lógica de procesamiento y almacenamiento de los datos del formulario. Es responsable de gestionar la creación de nuevos posts en el CPT basado en los datos del formulario.

class-shortcode-handler.php: Define los shortcodes que se pueden utilizar en el contenido de WordPress para mostrar formularios o datos en el front-end.

class-gutenberg-block.php: Registra y maneja el bloque de Gutenberg que permite insertar el formulario en el editor de bloques de WordPress.

3. Carpeta assets

Función: Contiene los archivos estáticos, como hojas de estilo CSS y scripts JavaScript, que son necesarios para el correcto funcionamiento del plugin.

Archivos:

css/styles.css: Define el estilo visual del formulario y otros elementos que el plugin pueda introducir en el front-end.

js/scripts.js: Contiene la lógica JavaScript necesaria para interactuar con el formulario, como la validación o el manejo de envíos mediante AJAX.

4. Archivo shortcodes.php

Función: Define los shortcodes disponibles que se pueden utilizar en las páginas o entradas de WordPress para mostrar formularios o listas de datos almacenados por el plugin.

Lógica:

Shortcodes: Este archivo es clave para integrar la funcionalidad del plugin dentro del contenido de WordPress. Los shortcodes permiten a los usuarios insertar formularios o listas de registros en cualquier parte del sitio sin necesidad de codificar.

5. Configuración de las Reglas de Reescritura

Función: Se configuran reglas personalizadas para las URLs del plugin, lo que permite una estructura de URL específica para acceder a los formularios creados.

Lógica:

Reescritura de URLs: La regla de reescritura cambia cómo WordPress maneja ciertas URLs para que, por ejemplo, /id/mi_plugin_formulario/ apunte al contenido adecuado dentro del CPT.

Variables de Consulta: Se añade edit_id como una variable de consulta para identificar qué formulario específico se está editando o viendo.

6. Shortcode de Edición

Función: Permite la edición de los formularios directamente desde el front-end utilizando un shortcode específico.

Lógica:

Formulario de Edición: Carga y muestra un formulario pre-rellenado con los datos del registro existente, permitiendo al usuario actualizar la información.