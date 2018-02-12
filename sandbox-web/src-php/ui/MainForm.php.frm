{
  "title": "MainForm",
  "router": {
    "path": "/"
  },
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
        "opacity": 0,
        "style": "border-bottom: 1px solid silver; color: gray;",
        "padding": [0, 0, 30, 0],
        "components": [
          {
            "_": "framework\\web\\ui\\animations\\UIFadeAnimation"
          }
        ]
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
        "target": "_blank",
        "components": [
          {
            "_": "components\\HelloWorldComponent",
            "when": "render"
          }
        ]
      }
    ]
  }
}