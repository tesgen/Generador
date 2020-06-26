<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nested grids demo</title>

    <link rel="stylesheet" href="{{asset('css/demo.css')}}">
    <link rel="stylesheet" href="{{asset('css/gridstack.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/gridstack-extra.css')}}">

</head>
<body>
<div class="container-fluid">
    <h1>Nested grids demo</h1>
    <br>

    <div class="grid-stack nested1"></div>
    <br>
    <a onClick="addNewWidget()" class="btn btn-primary" href="#">Add New</a>
    <a onClick="saveGrid()" class="btn btn-primary" href="#">Save</a>
    <a onClick="send()" class="btn btn-primary" href="#">Generate</a><br><br>
    <textarea id="saved-data" cols="100" rows="20" readonly="readonly"></textarea>

</div>

<script src="{{asset('js/coreui/jquery-3.4.1.min.js')}}"></script>
<script src="{{asset('js/gridstack.all.js')}}"></script>

<script type="text/javascript">

    var nestOptions = {
        acceptWidgets: '.grid-stack-item.sub',
        dragOut: false,
        resizable: {
            handles: 'e'
        },
        animate: true,
        float: true
        // cellHeight: '60px'
    };

    GridStack.init(null, '.grid-stack.top');
    // GridStack.init(null, '.grid-stack.item');

    var grid = GridStack.init(nestOptions, '.grid-stack.nested1');
    grid.column(12);

    nestOptions.dragOut = false;

    grid.on('added', function (e, items) {
        log('added ', items);
    });

    grid.on('removed', function (e, items) {
        log('removed ', items);

    });

    grid.on('change', function (e, items) {
        log('change ', items);
    });

    function log(type, items) {
        var str = '';
        items.forEach(function (item) {
            str += ' (x,y)=' + item.x + ',' + item.y;
        });
        // console.log(type + items.length + ' items.' + str);
    }

    var items = [
        {x: 0, y: 0, width: 12, height: 1},
        {x: 0, y: 1, width: 12, height: 1},
        {x: 0, y: 2, width: 12, height: 1}
    ];

    var count = 0;

    grid.batchUpdate();

    for (var i = 0; i < items.length; i++) {
        var n = {
            x: items[i].x,
            y: items[i].y,
            width: items[i].width,
            height: items[i].height
        };
        add(n);
    }

    grid.commit();

    function addNewWidget() {
        var n = {
            x: 0,
            y: grid.engine.nodes.length,
            width: 12,
            height: 1
        };
        add(n);
    }


    function add(n) {
        grid.addWidget('<div class="grid-stack-item sub"><div class="grid-stack-item-content">' +
            '<button style="float: right;" onClick="removeWidget(this.parentNode.parentNode)">X</button><br>'
            + count++ + (n.text ? n.text : '') + '</div></div>', n);
    }

    function removeWidget(parentNode) {
        grid.removeWidget(parentNode);
    }

    saveGrid = function() {

        serializedData = [];

        grid.engine.nodes = GridStack.Utils.sort(grid.engine.nodes, 1);

        grid.engine.nodes.forEach(function(node) {
            serializedData.push({
                x: node.x,
                y: node.y,
                width: node.width,
                height: node.height,
                id: node._id
            });
        });

        // serializedData.sort(sortByPosition);

        document.querySelector('#saved-data').value = JSON.stringify(serializedData, null, '  ');

    };

    function sortByPosition(a, b){
        if (a.y === b.y) return a.x - b.x;
        return a.y - b.y;
    }

    function send() {
        saveGrid();
        $.ajax({
            type: 'POST',
            url: '{{url('/generador/generar_form')}}',
            data: JSON.stringify(serializedData),
            success: function (data) {
                console.log('Generado correctamente');
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>
</body>
</html>
