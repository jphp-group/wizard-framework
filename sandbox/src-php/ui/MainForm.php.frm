{
  "title": "MainForm",
  "view": {
      "_": "VBox",
      "align": ["center", "center"],
      "height": "100%",
      "spacing": 10,

      "_content": [
        {
            "_": "AnchorPane",
            "style": "border: 1px solid black;",
            "width": 300,
            "height": 300,
            "id": "pane",
            "_content": [
                {
                  "_": "Button",
                  "id": "button",
                  "kind": "success",
                  "text": "Добавить картинку",
                  "classes": ["foobar"],
                  "x": 30,
                  "y": 50,
                  "tooltip": {
                      "_": "Label", "text": "Нажмите на кнопку2"
                  }
                }
            ]
        }
      ]
  }
}