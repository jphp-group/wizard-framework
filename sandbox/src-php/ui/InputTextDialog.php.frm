{
  "title": "Input Text",
  "layout": {
    "_": "VBox",
    "spacing": 10,
    "width": 400,
    "_content": [
        {
          "_": "Label",
          "text": "Введите полный URL к картинке:"
        },
        {
          "_": "HBox",
          "align": ["center", "center"],
          "spacing": 10,
          "width": "100%",

          "_content": [
            {
              "_": "TextField",
              "id": "input",
              "placeholder": "Введите URL к картинке",
              "width": "100%"
            },
            {
              "_": "Button",
              "id": "done",
              "text": "OK",
              "kind": "primary"
            }
          ]
        }
    ]
  }
}