<!-- The file upload form used as target for the file upload widget -->
<div id="fileupload">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="http://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="fa fa-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="file" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="fa fa-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="fa fa-ban"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="fa fa-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!--<input type="checkbox" class="toggle"> -->
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </div>

<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
                {% if (file.fileID) { %}
                    <input class="upload-field-ids" type="hidden" name="images[]" value="{%=file.fileID%}">
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>

{{ HTML::script('assets/js/libs/tmpl.min.js') }}
{{ HTML::script('assets/js/libs/load-image.all.min.js') }}
{{ HTML::script('assets/js/libs/jquery.iframe-transport.js') }}
{{ HTML::script('assets/js/libs/jquery.fileupload.js') }}
{{ HTML::script('assets/js/libs/jquery.fileupload-process.js') }}
{{ HTML::script('assets/js/libs/jquery.fileupload-image.js') }}
{{ HTML::script('assets/js/libs/jquery.fileupload-validate.js') }}
{{ HTML::script('assets/js/libs/jquery.fileupload-ui.js') }}
    {{ HTML::style('assets/js/libs/jquery.fileupload.css') }}

<!-- Fallback for IE 8 & 10 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="assets/js/libs/jquery.xdr-transport.js"></script>
<![endif]-->

<script>
    /*
     * jQuery File Upload stuff
     * jQuery minicolors stuff
     */

    /* global $, window */

    $(function () {
        'use strict';
        var jupload = $('#fileupload');
        // Initialize the jQuery File Upload widget:
        jupload.fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: '/upload/',
            acceptFileTypes: /(\.|\/)(gif|jpe?g|png|mp4)$/i,
            maxFileSize: 40000000, // 10 MB
            maxNumberOfFiles: 25
        }).on('fileuploadprocessalways', function (e, data) {
            var currentFile = data.files[data.index];
            if (data.files.error && currentFile.error) {
              // there was an error, do something about it
              //console.log(currentFile.error);
            }
        });
    
        // from http://stackoverflow.com/a/21728472
        if (typeof existingfiles !== 'undefined'){
            jupload.fileupload('option', 'done').call(jupload, $.Event('done'), {result: existingfiles});
        };

    });
</script>