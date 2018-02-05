package org.develnext.jphp.ext.jcef.classes;

import org.cef.browser.CefBrowser;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;


@Name("CefBrowser")
@Namespace(JCefExtension.NS)
public class PCefBrowser extends BaseWrapper<CefBrowser> {
    public PCefBrowser(Environment env, CefBrowser wrappedObject) {
        super(env, wrappedObject);
    }

    public PCefBrowser(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private void __construct() {
    }

    @Signature
    public void executeJavaScript(String script) {
        executeJavaScript(script, null);
    }

    @Signature
    public void executeJavaScript(String script, @Nullable String url) {
        executeJavaScript(script, url, 0);
    }

    @Signature
    public void executeJavaScript(String script, @Nullable String url, int line) {
        getWrappedObject().executeJavaScript(script, url, line);
    }

    @Signature
    public PCefBrowser getDevTools(Environment env) {
        CefBrowser cefBrowser = getWrappedObject().getDevTools();
        return new PCefBrowser(env, cefBrowser);
    }
}
