{
  "title": "Code editor",
  "router": {
    "path": "/code"
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
        "text": "Wizard Framework (Code editor)",
        "font": {"size": 22},
        "width": "100%",
        "padding": [0, 0, 30, 0]
      },
      {
        "_": "HBox",
        "align": [
          "top",
          "center"
        ],
        "padding": 10,
        "spacing": 10,
        "_content": [
          {
            "_": "ComboBox",
            "id": "themes",
            "selectedText": "eclipse"
          },
          {
            "_": "ComboBox",
            "id": "mode",
            "selectedText": "json"
          }
        ]
      },
      {
        "_": "bundle\\aceeditor\\ui\\UIAceEditor",
        "id": "editor",
        "height": "100%",
        "mode": "json",
        "theme": "eclipse"
      }
    ]
  }
}