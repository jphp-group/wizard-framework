{
  "title": "MainForm",
  "layout": {
    "_": "VBox",
    "align": [
      "top",
      "center"
    ],
    "padding": 30,
    "height": "100%",
    "spacing": 10,
    "_content": [
      {
        "_": "Label",
        "text": "Wizard Framework (Демонстрация)",
        "font": {"size": 22},
        "width": "100%",
        "style": "border-bottom: 1px solid silver; color: gray;",
        "padding": [0, 0, 30, 0]
      },
      {
        "_": "Label",
        "text": "Список заданий",
        "font": {"size": 45}
      },
      {
        "_": "HBox",
        "spacing": 10,
        "width": 400,
        "_content": [
          {
            "_": "TextField",
            "id": "input",
            "placeholder": "Введите текст задания",
            "width": "100%"
          },
          {
            "_": "Button",
            "id": "button",
            "kind": "info",
            "text": "Добавить"
          }
        ]
      },
      {
        "_": "bundle\\aceeditor\\ui\\UIAceEditor",
        "id": "editor",
        "theme": "monokai",
        "mode": "php",
        "height": 300,
        "width": 800,
        "font": {"size": 14},
        "editable": false,
        "visible": false
      },
      {
        "_": "VBox",
        "spacing": 10,
        "width": 400,
        "horAlign": "left",
        "id": "quests"
      },
      {
        "_": "Button",
        "id": "clear",
        "kind": "danger",
        "text": "Удалить все записи",
        "width": 400,
        "graphic": {"_": "Icon", "kind": "clear"},
        "visible": false
      },
      {
        "_": "Hyperlink",
        "text": "Автор - Дмитрий Зайцев",
        "href": "#",
        "target": "_blank"
      }
    ]
  }
}