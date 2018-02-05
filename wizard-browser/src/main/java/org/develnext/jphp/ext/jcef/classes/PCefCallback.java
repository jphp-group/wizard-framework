package org.develnext.jphp.ext.jcef.classes;

import org.cef.callback.CefCallback;
import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.Name;
import php.runtime.annotation.Reflection.Namespace;
import php.runtime.annotation.Reflection.Signature;
import php.runtime.env.Environment;
import php.runtime.lang.BaseWrapper;
import php.runtime.reflection.ClassEntity;


@Name("CefCallback")
@Namespace(JCefExtension.NS)
public class PCefCallback extends BaseWrapper<CefCallback> {
    public PCefCallback(Environment env, CefCallback wrappedObject) {
        super(env, wrappedObject);
    }

    public PCefCallback(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature
    private void __construct() {
    }

    @Signature
    public Memory Continue(Environment env, Memory... args) {
        this.getWrappedObject().Continue();
        return Memory.NULL;
    }

    @Signature
    public void cancel() {
        getWrappedObject().cancel();
    }
}
