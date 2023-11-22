var app = {
    // Application Constructor
    initialize: function () {
        document.addEventListener('deviceready', this.onDeviceReady.bind(this), false);
    },

    // deviceready Event Handler
    //
    // Bind any cordova events here. Common events are:
    // 'pause', 'resume', etc.
    onDeviceReady: function () {
        try {
            //Prevent back button
            document.addEventListener("backbutton", function (e) {
                e.preventDefault();
            }, false);
            start();
        } catch (err) {
        }
        this.receivedEvent('deviceready');
    },

    // Update DOM on a Received Event
    receivedEvent: function (id) {
        var parentElement = document.getElementById(id);
        //console.log('Received Event: ' + id);
    }
};

app.initialize();

function syncdb(download) {
    syncAudits();
    window.resolveLocalFileSystemURL(cordova.file.applicationStorageDirectory + 'databases/' + 'db4.db',
        function () {
            //==============>
            window.db = window.sqlitePlugin.openDatabase({ name: 'db4.db', location: 'default' });
            db.transaction(function (tx) {
                tx.executeSql('select Id, tag, datetimecaptured, itemcode, status, qty, location, iduser, openbox, tosync, reasontoedit, auditcounter, resultaudit, iduseraudit, datetimeaudit, lot, packingdate, uom from tags where tosync=1', [], function (tx, res) {
                    var datatosend = [];
                    var ids = '';
                    for (var i = 0; i < res.rows.length > 0; i++) {
                        var currenttag = res.rows.item(i);
                        datatosend.push(currenttag);
                        ids += currenttag.Id + ',';
                    }
                    if (ids != '') {
                        ids = ids.substring(0, ids.length - 1);
                    }
                    //alert(JSON.stringify(datatosend));
                    //alert(window.localStorage.apiurl + "/webservices/receiveTags");
                    if (datatosend.length > 0) {
                        $.post(window.localStorage.apiurl + "/webservices/receiveTags", { data: datatosend }, function (result) {
                            if (result == 'ok') {
                                if (download) {
                                    downloaddb();
                                } else {
                                    tosync0(ids);
                                }
                            } else {
                                window.plugins.toast.showWithOptions({
                                    message: 'Problem: ' + result,
                                    duration: "long",
                                    position: "top",
                                    styling: {
                                        backgroundColor: '#ff9999'
                                    }
                                });
                            }
                        }).fail(function (xhr, status, error) {
                            $('#lblstatus').html(xhr.responseText);
                            window.plugins.toast.showWithOptions({
                                message: 'Problem: ' + xhr.responseText,
                                duration: "long",
                                position: "top",
                                styling: {
                                    backgroundColor: '#ff9999'
                                }
                            });
                        });
                    } else {
                        if (download) {
                            downloaddb();
                        }
                    }
                }, function (error) {
                    
                    if (download) {
                        window.plugins.toast.showWithOptions({
                            message: 'Local database is corrupted. A new it will be downloaded.',//error.message,
                            duration: "long",
                            position: "top",
                            styling: {
                                backgroundColor: '#ff9999'
                            }
                        });
                        downloaddb();
                    } else {
                        window.plugins.toast.showWithOptions({
                            message: 'Local database is corrupted. Download it again.',//error.message,
                            duration: "long",
                            position: "top",
                            styling: {
                                backgroundColor: '#ff9999'
                            }
                        });
                    }
                });
            }, function (error) {
            
                //alert('6'+error.message);
                window.plugins.toast.showWithOptions({
                    message: error.message,
                    duration: "long",
                    position: "top",
                    styling: {
                        backgroundColor: '#ff9999'
                    }
                });
            });
            //==============>
        }, function () {
            downloaddb();
        });
}
function tosync0(ids) {
    window.db = window.sqlitePlugin.openDatabase({ name: 'db4.db', location: 'default' });
    db.transaction(function (tx) {
        //alert('update tags set tosync=0 where Id in ('+ids+')');
        tx.executeSql('update tags set tosync=0 where Id in (' + ids + ')', [], function (tx, res) {
            window.plugins.toast.showWithOptions({
                message: 'Successfully sync',
                duration: "long",
                position: "top",
                styling: {
                    backgroundColor: '#006600'
                }
            });
        }, function (error) {
            //alert('5'+error.message);
            window.plugins.toast.showWithOptions({
                message: error.message,
                duration: "long",
                position: "top",
                styling: {
                    backgroundColor: '#ff9999'
                }
            });
        });
    }, function (error) {
        //alert('6'+error.message);
        window.plugins.toast.showWithOptions({
            message: error.message,
            duration: "long",
            position: "top",
            styling: {
                backgroundColor: '#ff9999'
            }
        });
    });
}
function tosyncAuditsIds() {
    window.db = window.sqlitePlugin.openDatabase({ name: 'db4.db', location: 'default' });
    db.transaction(function (tx) {
        tx.executeSql('update audits set tosync=0 where Id IS NULL', [], function (tx, res) {
           
        }, function (error) {
            window.plugins.toast.showWithOptions({
                message: JSON.stringify(error),
                duration: "long",
                position: "top",
                styling: {
                    backgroundColor: '#ff9999'
                }
            });
        });
    }, function (error) {
        window.plugins.toast.showWithOptions({
            message: JSON.stringify(error),
            duration: "long",
            position: "top",
            styling: {
                backgroundColor: '#ff9999'
            }
        });
    });
}
function syncAudits(){
    window.resolveLocalFileSystemURL(cordova.file.applicationStorageDirectory + 'databases/' + 'db4.db',
        function () {
            //==============>
            window.db = window.sqlitePlugin.openDatabase({ name: 'db4.db', location: 'default' });
            db.transaction(function (tx) {
                tx.executeSql('select Id, tag, itemcode, qty, location, reasontoedit, resultaudit, iduseraudit, datetimeaudit, openbox, auditcounter, uom from audits where tosync=1', [], function (tx, res) {
                    var datatosend = [];
                    for (var i = 0; i < res.rows.length > 0; i++) {
                        var currentaudit = res.rows.item(i);
                        datatosend.push(currentaudit);
                    }
                    
                    if (datatosend.length > 0) {
                        $.post(window.localStorage.apiurl + "/webservices/receiveAudits", { data: datatosend }, function (result) {
                            if (result == 'ok') {
                                tosyncAuditsIds();
                            } else {
                                window.plugins.toast.showWithOptions({
                                    message: 'Problem: ' + result,
                                    duration: "long",
                                    position: "top",
                                    styling: {
                                        backgroundColor: '#ff9999'
                                    }
                                });
                            }
                        }).fail(function (xhr, status, error) {
                            $('#lblstatus').html(xhr.responseText);
                            window.plugins.toast.showWithOptions({
                                message: 'Problem: ' + xhr.responseText,
                                duration: "long",
                                position: "top",
                                styling: {
                                    backgroundColor: '#ff9999'
                                }
                            });
                        });
                    } 
                }, function (error) {
                    window.plugins.toast.showWithOptions({
                        message: 'Local database is corrupted.',//error.message,
                        duration: "long",
                        position: "top",
                        styling: {
                            backgroundColor: '#ff9999'
                        }
                    });
                });
            }, function (error) {
                //alert('6'+error.message);
                window.plugins.toast.showWithOptions({
                    message: error.message,
                    duration: "long",
                    position: "top",
                    styling: {
                        backgroundColor: '#ff9999'
                    }
                });
            });
            //==============>
        });
}
function downloaddb() {
    var fileTransfer = new FileTransfer();
    var uri = encodeURI(window.localStorage.apiurl + "/webservices/getdatabase");
    var fileURL = cordova.file.applicationStorageDirectory + 'databases/' + 'db4.db';

    fileTransfer.onprogress = function (progressEvent) {
        if (progressEvent.lengthComputable) {
            var pprogress = Math.round((progressEvent.loaded / progressEvent.total) * 100) * 100 / 100;
            $('#btndownload').val(pprogress + '%');
            //loadingStatus.setPercentage(progressEvent.loaded / progressEvent.total);
        } else {
            //loadingStatus.increment();
        }
    };
    fileTransfer.download(
        uri,
        fileURL,
        function (entry) {
            $('#btndownload').prop('disabled', false).val('Download database');
            window.localStorage.apiurl = $('#serverurl').val();
            //alert("download complete: " + entry.toURL());
            //listDir(cordova.file.applicationStorageDirectory+'databases/');
            //testdownloadeddb();
            window.location.href = "index.html";
        },
        function (error) {
            $('#btndownload').prop('disabled', false).val('Download database');
            //alert('Error: Unable to access api url.');
            alert(JSON.stringify(error));
        },
        false,
        {
            headers: {//Pendiente implementar esto
                "Authorization": "Basic dGVzdHVzZXJuYW1lOnRlc3RwYXNzd29yZA=="
            }
        }
    );
}