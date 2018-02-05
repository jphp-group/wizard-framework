package org.develnext.jphp.ext.jcef.classes;

import org.develnext.jphp.ext.jcef.JCefExtension;
import php.runtime.Memory;
import php.runtime.annotation.Reflection;
import php.runtime.annotation.Reflection.*;
import php.runtime.common.HintType;
import php.runtime.env.Environment;
import php.runtime.ext.core.classes.stream.Stream;
import php.runtime.lang.BaseObject;
import php.runtime.reflection.ClassEntity;

@Name("CefResourceHandler")
@Namespace(JCefExtension.NS)
abstract public class PCefResourceHandler extends BaseObject {
    public PCefResourceHandler(Environment env, ClassEntity clazz) {
        super(env, clazz);
    }

    @Signature({
            @Arg(value = "request", type = HintType.ARRAY),
            @Arg(value = "callback", nativeType = PCefCallback.class)
    })
    abstract public Memory processRequest(Environment env, Memory... args);

    @Signature({
            @Arg(value = "response", nativeType = PCefResponse.class),
            @Arg(value = "args", type = HintType.ARRAY)
    })
    abstract public Memory getResponseHeaders(Environment env, Memory... args);

    @Signature({
            @Arg(value = "out", nativeType = Stream.class),
            @Arg(value = "bytesToRead", type = HintType.INT),
            @Arg(value = "callback", nativeType = PCefCallback.class)
    })
    abstract public Memory readResponse(Environment env, Memory... args);

    @Signature({
            @Arg(value = "cookie", type = HintType.ARRAY)
    })
    abstract public Memory canGetCookie(Environment env, Memory... args);

    @Signature({
            @Arg(value = "cookie", type = HintType.ARRAY)
    })
    abstract public Memory canSetCookie(Environment env, Memory... args);

    @Signature()
    abstract public Memory cancel(Environment env, Memory... args);
}
