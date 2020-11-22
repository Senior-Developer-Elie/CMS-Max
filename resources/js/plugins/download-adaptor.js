var Download_Adapter = {
    zip: false,
    completedDownloads: 0,
    process: function(data, callback = null){
        if( data.type == 'zip' ){
            Download_Adapter.downloadAsZip(data, callback);
        }
        else if( data.type == 'single' ){
            Download_Adapter.downloaAsSingleFile(data, callback);
        }
    },

    downloaAsSingleFile: function(data, callback){
        fetch(data.public_url)
            .then(resp => resp.blob())
            .then(blob => {
                saveAs( blob, data.file_name)

                if( callback != null )
                    callback();
            })
            .catch(() => {
                if( callback != null )
                    callback();
            });
    },

    downloadAsZip: function(data, callback){
        Download_Adapter.zip = new JSZip();

        data.files.forEach(function(file){
            fetch(file.public_url)
            .then(resp => resp.blob())
            .then(blob => {
                let filename = file.origin_name.replace(/.*\//g, "");
                Download_Adapter.zip.file(filename, blob, {createFolders: true});
                Download_Adapter.completedDownloads++;
                if( Download_Adapter.completedDownloads == data.files.length )
                    Download_Adapter.finishZip(data.zipFileName, callback);
            })
            .catch(() => {
                Download_Adapter.completedDownloads++;
                if( Download_Adapter.completedDownloads == data.files.length )
                    Download_Adapter.finishZip(data.zipFileName, callback);
            });
        });
    },

    finishZip: function(zipFileName, callback){
        Download_Adapter.zip.generateAsync({type:"blob"})
            .then(function(content) {
                saveAs(content, zipFileName);

                if( callback != null )
                    callback();
            });
    }
};
