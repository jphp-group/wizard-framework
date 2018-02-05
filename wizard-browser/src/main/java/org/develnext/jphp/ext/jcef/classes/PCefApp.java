package org.develnext.jphp.ext.jcef.classes;

import org.cef.CefApp;
import org.cef.CefSettings;
import org.cef.browser.CefBrowser;
import org.cef.browser.CefFrame;
import org.cef.callback.CefCallback;
import org.cef.callback.CefCommandLine;
import org.cef.callback.CefSchemeHandlerFactory;
import org.cef.callback.CefSchemeRegistrar;
import org.cef.handler.*;
import org.cef.misc.IntRef;
import org.cef.misc.StringRef;
import org.cef.network.CefCookie;
import org.cef.network.CefRequest;
import org.cef.network.CefResponse;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.exceptions.CriticalException;
import php.runtime.ext.core.classes.stream.MemoryStream;
import php.runtime.ext.core.classes.stream.MiscStream;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.invoke.DynamicMethodInvoker;
import php.runtime.invoke.Invoker;
import php.runtime.lang.BaseWrapper;
import php.runtime.lang.IObject;
import php.runtime.memory.ArrayMemory;
import php.runtime.memory.BinaryMemory;
import php.runtime.memory.ObjectMemory;
import php.runtime.memory.StringMemory;
import php.runtime.reflection.ClassEntity;
import php.runtime.reflection.support.ReflectionUtils;

import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

@Name("CefApp")
@Namespace(JCefExtension.NS)
public class PCefApp extends BaseWrapper<CefApp> {
    public PCefApp(Environment env, CefApp wrappedObject) {
        super(env, wrappedObject);
    }

    public PCefApp(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    private static List<CefAppHandlerAdapter> appHandlerAdapters = null;

    @Signature
    private void __construct() {
        getWrappedObject().getVersion();
    }

    @Signature
    public Memory getVersion(Environment env) {
        return ArrayMemory.ofNullableBean(env, getWrappedObject().getVersion());
    }

    @Signature
    public void dispose() {
        getWrappedObject().dispose();
    }

    @Signature
    public PCefClient createClient(Environment env) {
        return new PCefClient(env, getWrappedObject().createClient());
    }

    @Signature
    public void setSettings(Environment env, ArrayMemory settings) {
        getWrappedObject().setSettings(settings.toBean(env, CefSettings.class));
    }

    @Signature
    public static CefApp.CefAppState getState() {
        return CefApp.getState();
    }

    @Signature
    public static CefApp getInstance(Environment env) {
        return CefApp.getInstance();
    }

    @Signature
    public static CefApp getInstance(Environment env, String[] args) {
        return getInstance(env, args, new ArrayMemory());
    }

    @Signature
    public static CefApp getInstance(Environment env, String[] args, ArrayMemory settings) {
        CefSettings cefSettings = settings.toBean(env, CefSettings.class);
        return CefApp.getInstance(args, cefSettings);
    }

    private static List<CefAppHandlerAdapter> getAppHandlerAdapters() {
        if (appHandlerAdapters == null) {
            appHandlerAdapters = new ArrayList<>();
            CefApp.addAppHandler(
                    new CefAppHandlerAdapter(null) {
                        @Override
                        public void onBeforeCommandLineProcessing(String process_type, CefCommandLine command_line) {
                            appHandlerAdapters.forEach(el -> el.onBeforeCommandLineProcessing(process_type, command_line));
                        }

                        @Override
                        public boolean onBeforeTerminate() {
                            return appHandlerAdapters.stream().map(el -> el.onBeforeTerminate()).anyMatch(aBoolean -> aBoolean);
                        }

                        @Override
                        public void stateHasChanged(CefApp.CefAppState state) {
                            appHandlerAdapters.forEach(el -> el.stateHasChanged(state));
                        }

                        @Override
                        public void onRegisterCustomSchemes(CefSchemeRegistrar registrar) {
                            appHandlerAdapters.forEach(el -> el.onRegisterCustomSchemes(registrar));
                        }

                        @Override
                        public void onContextInitialized() {
                            appHandlerAdapters.forEach(el -> el.onContextInitialized());
                        }

                        @Override
                        public CefPrintHandler getPrintHandler() {
                            return super.getPrintHandler();
                        }

                        @Override
                        public void onScheduleMessagePumpWork(long delay_ms) {
                            super.onScheduleMessagePumpWork(delay_ms);
                        }
                    }
            );
        }

        return appHandlerAdapters;
    }

    @Signature
    public static void onBeforeTerminate(Invoker invoker) {
        getAppHandlerAdapters().add(new CefAppHandlerAdapter(new String[0]) {
            @Override
            public boolean onBeforeTerminate() {
                return invoker.callAny().toBoolean();
            }
        });
    }

    @Signature
    public static void onContextInitialized(Invoker invoker) {
        getAppHandlerAdapters().add(new CefAppHandlerAdapter(new String[0]) {
            @Override
            public void onContextInitialized() {
                invoker.callAny();
            }
        });
    }

    @Signature
    public static void onStateChanged(Invoker invoker) {
        getAppHandlerAdapters().add(new CefAppHandlerAdapter(new String[0]) {
            @Override
            public void stateHasChanged(CefApp.CefAppState state) {
                invoker.callAny(state);
            }
        });
    }

    @Signature
    public static void registerCustomScheme(Environment env, String schemeName, Invoker factory) {
        registerCustomScheme(env, schemeName, factory, true);
    }

    @Signature
    public static void registerCustomScheme(Environment env, String schemeName, Invoker factory, boolean isStandard) {
        getAppHandlerAdapters().add(new CefAppHandlerAdapter(new String[0]) {
            @Override
            public void onRegisterCustomSchemes(CefSchemeRegistrar registrar) {
                registrar.addCustomScheme(
                        schemeName,
                        true, false, false,
                        false, false, false
                );
            }

            @Override
            public void onContextInitialized() {
                CefApp cefApp = CefApp.getInstance();
                cefApp.registerSchemeHandlerFactory(schemeName, "", (browser, frame, schemeName1, request) -> {
                    Memory handler = factory.callAny(
                            new PCefBrowser(env, browser), schemeName, ArrayMemory.ofNullableBean(env, request)
                    );

                    if (handler.isNull()) return null;

                    if (!handler.instanceOf(PCefResourceHandler.class)) {
                        env.exception("Invoker must return an instance of " + ReflectionUtils.getClassName(PCefResourceHandler.class) + ", " + ReflectionUtils.getGivenName(handler) + " given");
                    }

                    Invoker processRequest = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("processRequest")));
                    Invoker getResponseHeaders = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("getResponseHeaders")));
                    Invoker readResponse = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("readResponse")));
                    Invoker canGetCookie = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("canGetCookie")));
                    Invoker canSetCookie = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("canSetCookie")));
                    Invoker cancel = Invoker.create(env, ArrayMemory.of(handler, StringMemory.valueOf("cancel")));

                    return new CefResourceHandler() {
                        @Override
                        public boolean processRequest(CefRequest request, CefCallback callback) {
                            return processRequest.callAny(ArrayMemory.ofNullableBean(env, request), new PCefCallback(env, callback)).toBoolean();
                        }

                        @Override
                        public void getResponseHeaders(CefResponse response, IntRef response_length, StringRef redirectUrl) {
                            ArrayMemory array = ArrayMemory.ofPair("length", response_length.get());
                            array.put("redirectUrl", StringMemory.valueOf(redirectUrl.get()));

                            Memory result = getResponseHeaders.callAny(new PCefResponse(env, response), array);

                            if (result.isArray()) {
                                ArrayMemory arrayMemory = result.toValue(ArrayMemory.class);
                                if (arrayMemory.containsKey("length")) {
                                    response_length.set(arrayMemory.valueOfIndex("length").toInteger());
                                }

                                if (arrayMemory.containsKey("redirectUrl")) {
                                    redirectUrl.set(arrayMemory.valueOfIndex("redirectUrl").toString());
                                }
                            }
                        }

                        @Override
                        public boolean readResponse(byte[] data_out, int bytes_to_read, IntRef bytes_read, CefCallback callback) {
                            MemoryStream memoryStream = new MemoryStream();
                            MiscStream out = new MiscStream(env, memoryStream);
                            readResponse.callAny(out, bytes_to_read, new PCefCallback(env, callback)).toBoolean();
                            memoryStream.seek(0);

                            byte[] srcBinaryBytes = memoryStream.readFully();

                            if (srcBinaryBytes == null || srcBinaryBytes.length == 0) {
                                bytes_read.set(0);
                                return false;
                            } else {
                                bytes_read.set(srcBinaryBytes.length);
                                System.arraycopy(srcBinaryBytes, 0, data_out, 0, srcBinaryBytes.length);
                                return true;
                            }
                        }

                        @Override
                        public boolean canGetCookie(CefCookie cookie) {
                            return canGetCookie.callAny(ArrayMemory.ofNullableBean(env, cookie)).toBoolean();
                        }

                        @Override
                        public boolean canSetCookie(CefCookie cookie) {
                            return canSetCookie.callAny(ArrayMemory.ofNullableBean(env, cookie)).toBoolean();
                        }

                        @Override
                        public void cancel() {
                            cancel.callAny();
                        }

                        @Override
                        public void setNativeRef(String identifer, long nativeRef) {
                        }

                        @Override
                        public long getNativeRef(String identifer) {
                            return 0;
                        }
                    };
                });
            }
        });
    }
}
