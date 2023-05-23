export let core = {
    formatBytes(fileSizeBytes, decimals = 2) {
        var fileSize, unit;
        if (fileSizeBytes < 1024) {
            fileSize = fileSizeBytes;
            unit = 'bytes';
        } else if (fileSizeBytes < 1024 * 1024) {
            fileSize = (fileSizeBytes / 1024).toFixed(2);
            unit = 'KB';
        } else if (fileSizeBytes < 1024 * 1024 * 1024) {
            fileSize = (fileSizeBytes / (1024 * 1024)).toFixed(2);
            unit = 'MB';
        } else {
            fileSize = (fileSizeBytes / (1024 * 1024 * 1024)).toFixed(2);
            unit = 'TB';
        }
        return fileSize + ' ' + unit;
    }
}
