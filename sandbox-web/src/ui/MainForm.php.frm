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
        "text": "Wizard Framework (TDOD лист)",
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
            "width": "100%",
            "components": [
              {
                "_": "framework\\web\\ui\\effects\\UIShadowEffect"
              }
            ]
          },
          {
            "_": "Button",
            "id": "button",
            "kind": "info",
            "text": "Добавить",
            "components": [
              {
                "_": "framework\\web\\ui\\effects\\UIShadowEffect",
                "id": "shadow",
                "radius": 15
              },
              {
                "_": "framework\\web\\ui\\effects\\UIShadowEffect",
                "id": "innerShadow",
                "color": "white",
                "radius": 5,
                "inner": true
              }
            ]
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
        "text": "Ссылка в никуда :D",
        "href": "#",
        "target": "_blank",
        "components": [
          {
            "_": "framework\\web\\ui\\animations\\UICSSAnimation",
            "when": "hover",
            "reverseAnimated": false,
            "frames": [
                {"opacity": 1, "font-size": 15},
                {"opacity": 0.3, "font-size": 25},
                {"opacity": 1, "font-size": 15}
            ],
            "duration": "1s",
            "loop": true
          }
        ]
      }
    ]
  }
}