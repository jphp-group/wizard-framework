# Wizard Framework

Wizard Framework is a jphp framework for the web/mobile/desktop.

# How to taste?

If you want to see our framework in action, you can use the `sandbox` project in the `sandbox/` directory.

Before you should install Java 8 and run the `npmInstall` gradle task (it can take a lot of time):
```
cd <path_to_wizard_dir>

// for linux
chmod +x gradlew
./gradlew npmInstall

// for windows
gradlew.bat npmInstall
```

Then you can run wizard sandbox:

```
cd <path_to_wizard_dir>

// for linux
./gradlew sandbox

// for windows
gradlew.bat sandbox
```

Then open `http://localhost:5000/app` in your browser (opera, chrome or firefox). Your browser
must support the WebSocket feature.


