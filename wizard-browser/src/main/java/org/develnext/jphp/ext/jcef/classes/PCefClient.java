package org.develnext.jphp.ext.jcef.classes;

import org.cef.CefClient;
import org.cef.browser.CefBrowser;
import org.cef.browser.CefFrame;
import org.cef.browser.CefMessageRouter;
import org.cef.callback.CefJSDialogCallback;
import org.cef.callback.CefQueryCallback;
import org.cef.handler.*;
import org.cef.misc.BoolRef;
import org.cef.network.CefRequest;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Nullable;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.memory.ArrayMemory;
import php.runtime.reflection.ClassEntity;

import javax.swing.*;

@Name("CefClient")
@Namespace(JCefExtension.NS)
public class PCefClient extends BaseWrapper<CefClient> {
    public PCefClient(Environment env, CefClient wrappedObject) {
        super(env, wrappedObject);
    }

    public PCefClient(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private void __construct() {
    }

    @Signature
    public PCefBrowser createBrowser(Environment env, @Nullable String url, boolean isOffscreenRendered) {
        return createBrowser(env, url, isOffscreenRendered, false);
    }

    @Signature
    public PCefBrowser createBrowser(Environment env, @Nullable String url, boolean isOffscreenRendered, boolean isTransparent) {
        CefBrowser cefBrowser = getWrappedObject().createBrowser(url, isOffscreenRendered, isTransparent);
        return new PCefBrowser(env, cefBrowser);
    }

    @Signature
    public void addMessageRouter(Environment env, Invoker invoker) {
        CefMessageRouter messageRouter = CefMessageRouter.create();
        messageRouter.addHandler(new CefMessageRouterHandlerAdapter() {
            @Override
            public boolean onQuery(CefBrowser browser, CefFrame frame, long query_id, String _request, boolean persistent, CefQueryCallback callback) {
                return invoker.callAny(new PCefBrowser(env, browser), _request, persistent).toBoolean();
            }
        }, false);

        getWrappedObject().addMessageRouter(messageRouter);

    }

    @Signature
    public void onAfterCreated(Environment env, Invoker invoker) {
        getWrappedObject().addLifeSpanHandler(new CefLifeSpanHandlerAdapter() {
            @Override
            public void onAfterCreated(CefBrowser browser) {
                invoker.callAny(new PCefBrowser(env, browser));
            }
        });
    }

    @Signature
    public void onBeforeClose(Environment env, Invoker invoker) {
        getWrappedObject().addLifeSpanHandler(new CefLifeSpanHandlerAdapter() {
            @Override
            public void onBeforeClose(CefBrowser browser) {
                invoker.callAny(new PCefBrowser(env, browser));
            }
        });
    }

    @Signature
    public void onTitleChange(Environment env, Invoker invoker) {
        getWrappedObject().addDisplayHandler(new CefDisplayHandlerAdapter() {
            @Override
            public void onTitleChange(CefBrowser browser, String title) {
                invoker.callAny(new PCefBrowser(env, browser), title);
            }
        });
    }

    @Signature
    public void onConsoleMessage(Environment env, Invoker invoker) {
        getWrappedObject().addDisplayHandler(new CefDisplayHandlerAdapter() {
            @Override
            public boolean onConsoleMessage(CefBrowser browser, String message, String source, int line) {
                return invoker.callAny(new PCefBrowser(env, browser), message, source, line).toBoolean();
            }
        });
    }

    @Signature
    public void onAddressChange(Environment env, Invoker invoker) {
        getWrappedObject().addDisplayHandler(new CefDisplayHandlerAdapter() {
            @Override
            public void onAddressChange(CefBrowser browser, CefFrame frame, String url) {
                invoker.callAny(new PCefBrowser(env, browser), url);
            }
        });
    }

    @Signature
    public void onStatusMessage(Environment env, Invoker invoker) {
        getWrappedObject().addDisplayHandler(new CefDisplayHandlerAdapter() {
            @Override
            public void onStatusMessage(CefBrowser browser, String value) {
                invoker.callAny(browser, value);
            }
        });
    }

    @Signature
    public void onBeforeBrowse(Environment env, Invoker invoker) {
        getWrappedObject().addRequestHandler(new CefRequestHandlerAdapter() {
            @Override
            public boolean onBeforeBrowse(CefBrowser browser, CefFrame frame, CefRequest request, boolean is_redirect) {
                return invoker.callAny(new PCefBrowser(env, browser), ArrayMemory.ofNullableBean(env, request), is_redirect).toBoolean();
            }
        });
    }

    @Signature
    public void onBeforeResourceLoad(Environment env, Invoker invoker) {
        getWrappedObject().addRequestHandler(new CefRequestHandlerAdapter() {
            @Override
            public boolean onBeforeResourceLoad(CefBrowser browser, CefFrame frame, CefRequest request) {
                return invoker.callAny(new PCefBrowser(env, browser), ArrayMemory.ofNullableBean(env, request)).toBoolean();
            }
        });
    }
}
