package org.develnext.jphp.ext.jcef;

import org.cef.CefApp;
import org.cef.CefClient;
import org.cef.CefSettings;
import org.cef.OS;
import org.cef.browser.CefBrowser;
import org.cef.callback.CefCallback;
import org.cef.handler.CefAppHandlerAdapter;
import org.cef.network.CefResponse;
import org.develnext.framework.wizard.desktop.WizardBrowser;
import org.develnext.jphp.ext.jcef.classes.*;
import php.runtime.env.CompileScope;
import php.runtime.ext.support.Extension;

public class JCefExtension extends Extension {
    public static final String NS = "cef";

    @Override
    public Status getStatus() {
        return Status.EXPERIMENTAL;
    }

    @Override
    public void onRegister(CompileScope compileScope) {
        WizardBrowser.initialize();

        //CefApp instance = CefApp.getInstance(cefSettings);

        registerWrapperClass(compileScope, CefApp.class, PCefApp.class);
        registerWrapperClass(compileScope, CefClient.class, PCefClient.class);
        registerWrapperClass(compileScope, CefBrowser.class, PCefBrowser.class);
        registerClass(compileScope, PCefBrowserWindow.class);

        registerWrapperClass(compileScope, CefCallback.class, PCefCallback.class);
        registerWrapperClass(compileScope, CefResponse.class, PCefResponse.class);
        registerClass(compileScope, PCefResourceHandler.class);
    }
}
