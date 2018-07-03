console.log("Main Scripts");

function get(url, data = {}) {
  var d = [];
  $.ajax({
      async: false,
      url: url,
      type: 'GET',
      dataType: 'JSON',
      data: data
    })
    .done(function(a) {
      console.log(a);
      d = a;
    })
    .fail(function() {
      return false;
    })
    .always(function() {
      console.log("complete");
    });
  return d;
}

function selectize(config = {}, obj, url) {
  return obj.selectize({
    valueField: config.value,
    labelField: config.label,
    searchField: config.search,
    options: [],
    load: function(query, callback) {
      if (!query.length) return callback();
      $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data: {
          country: query,
        },
        error: function() {
          callback();
        },
        success: function(res) {
          callback(res);
        }
      });
    }
  });
}

function selectbuilder(data = [], obj, selected = []) {
  if (selected.length > 0) {
    console.log("Selected Detected");
    obj.append('<option value="' + selected[0].value + '" selected>' + selected[0].text + '</option>');
  }
  for (var i = 0; i < data.length; i++) {
    if (selected.length > 0) {
      if (data[i].value == selected[0].value) {
        continue;
      }
    }
    obj.append($("<option>", {
      value: data[i].value,
      text: data[i].text
    }));
  }
}

function slug(str) {
  str = str.replace(/^\s+|\s+$/g, ''); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
  }

  str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
    .replace(/\s+/g, '-') // collapse whitespace and replace by -
    .replace(/-+/g, '-'); // collapse dashes

  return str;
}

function ucfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function post(url, data = {}, enctype = false) {
  var d = [];
  if (enctype) {
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        dataType: 'JSON',
        processData: false,
        contentType: false,
        data: data
      })
      .done(function(a) {
        d = a;
      })
      .fail(function() {
        return false;
      })
      .always(function() {
        console.log("Request Ajax FILE From URL " + url + " Complete");
      });
  } else {
    $.ajax({
        async: false,
        url: url,
        type: 'POST',
        dataType: 'JSON',
        data: data
      })
      .done(function(a) {
        d = a;
      })
      .fail(function() {
        return false;
      })
      .always(function() {
        console.log("Request Ajax From URL " + url + " Complete");
      });
  }
  return d;
}

function table(columns = [], row = [], id) {
  thead = [];
  tbody = [];
  for (var i = 0; i < columns.length; i++) {
    thead[i] = "<th>" + columns[i] + "</th>";
  }
  for (var i = 0; i < row.length; i++) {
    tbody[i] = "<th>" + row[i] + "</th>";
  }
  cookingtable = [
    '<table class="table" id="' + id + '">',
    '<thead>',
    thead.join(""),
    '</thead>',
    '<tbody>',
    tbody.join(""),
    '</tbody>',
    '</table>'
  ];
  return cookingtable.join("");

}

function builder(input, button, id, button_del = true) {
  var inputboiler = [];
  for (var i = 0; i < input.length; i++) {
    if (input[i].value == undefined) {
      val = "";
    } else {
      val = input[i].value;
    }
    if (input[i].id == undefined) {
      ids = "";
    } else {
      ids = input[i].id;
    }
    if (input[i].step == undefined) {
      steps = "";
    } else {
      steps = "step='" + input[i].step + "'";
    }
    if (input[i].type == "select2") {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<select class="form-control " id="' + ids + '" name="' + input[i].name + '" ' + steps + '></select>',
        '</div>'
      ];
    } else if (input[i].type == "hidden") {
      temp = [
        '<div class="form-group">',
        '<input type="text" hidden id="' + ids + '" value="' + val + '" name="' + input[i].name + '">',
        '</div>'
      ];
    } else if (input[i].type == "disabled") {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<input type="text" class="form-control" disabled id="' + ids + '" value="' + val + '">',
        '</div>'
      ];
    } else if (input[i].type == "textarea") {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<textarea class="form-control" id="' + ids + '" name="' + input[i].name + '" ' + steps + '>' + val + '</textarea>',
        '</div>'
      ];
    } else if (input[i].type == "readonly") {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<input class="form-control" readonly type="' + input[i].type + '" id="' + ids + '" value="' + val + '" name="' + input[i].name + '" ' + steps + '>',
        '</div>'
      ];
    } else if (input[i].type == "password") {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<input class="form-control"  type="' + input[i].type + '" id="' + ids + '" value="' + val + '" name="' + input[i].name + '" ' + steps + '>',
        '</div>'
      ];
    } else {
      temp = [
        '<div class="form-group">',
        '<label>' + input[i].label + '</label>',
        '<input class="form-control" type="' + input[i].type + '" id="' + ids + '" value="' + val + '" name="' + input[i].name + '" ' + steps + '>',
        '</div>'
      ];
    }
    inputboiler[i] = temp.join("");
  }
  indexinput = inputboiler.length;
  buttontemp = [
    '<div class="form-group">',
    '<button class="btn btn-' + button.class + '" type="' + button.type + '">' + button.name + '</button>',
    '</div>'
  ];
  buttondel = [];
  if (button_del != true) {
    buttondel = [
      '<div class="form-group">',
      '<button class="btn btn-' + button_del.class + '" id="' + button_del.id + '" data-id="' + button_del.data + '" type="' + button_del.type + '">' + button_del.name + '</button>',
      '</div>'
    ]
  }
  inputboiler[indexinput] = buttontemp.join("");
  indexinput = inputboiler.length;
  inputboiler[indexinput] = buttondel.join("");
  cookinginput = inputboiler.join("");
  cookingform = [
    '<form method="post" onsubmit="return false" id="' + id + '">',
    cookinginput,
    '</form>'
  ];
  return cookingform.join("");
}
